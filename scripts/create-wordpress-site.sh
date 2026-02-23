#!/bin/bash
# Create a WordPress site using CloudPanel CLI + WP-CLI
# Usage: ./create-wordpress-site.sh <domain> <db_name> <db_user> <db_pass> <wp_user> <wp_pass> <wp_email> <site_title>

set -e

DOMAIN=$1
DB_NAME=$2
DB_USER=$3
DB_PASS=$4
WP_USER=$5
WP_PASS=$6
WP_EMAIL=$7
SITE_TITLE=$8

if [ -z "$DOMAIN" ] || [ -z "$DB_NAME" ]; then
    echo "Usage: $0 <domain> <db_name> <db_user> <db_pass> <wp_user> <wp_pass> <wp_email> <site_title>"
    exit 1
fi

SITE_DIR="/home/${DOMAIN}/htdocs/${DOMAIN}"
PHP_VERSION="8.2"
CLP_CLI="/usr/bin/clpctl"
WP_CLI="/usr/local/bin/wp"

echo "=== Creating WordPress site: ${DOMAIN} ==="

# Step 1: Create site in CloudPanel
echo "[1/8] Creating CloudPanel site..."
${CLP_CLI} site:add:php --domainName="${DOMAIN}" --phpVersion="${PHP_VERSION}" --vhostTemplate="WordPress" --siteUser="${DOMAIN}"

# Step 2: Create database
echo "[2/8] Creating database..."
${CLP_CLI} db:add --domainName="${DOMAIN}" --dbName="${DB_NAME}" --dbUserName="${DB_USER}" --dbUserPassword="${DB_PASS}"

# Step 3: Download WordPress
echo "[3/8] Downloading WordPress..."
cd "${SITE_DIR}"
sudo -u "${DOMAIN}" ${WP_CLI} core download --locale=en_US

# Step 4: Configure WordPress
echo "[4/8] Configuring WordPress..."
sudo -u "${DOMAIN}" ${WP_CLI} config create \
    --dbname="${DB_NAME}" \
    --dbuser="${DB_USER}" \
    --dbpass="${DB_PASS}" \
    --dbhost="localhost" \
    --dbcharset="utf8mb4"

# Step 5: Install WordPress
echo "[5/8] Installing WordPress..."
sudo -u "${DOMAIN}" ${WP_CLI} core install \
    --url="https://${DOMAIN}" \
    --title="${SITE_TITLE}" \
    --admin_user="${WP_USER}" \
    --admin_password="${WP_PASS}" \
    --admin_email="${WP_EMAIL}" \
    --skip-email

# Step 6: Install & activate Elementor
echo "[6/8] Installing Elementor..."
sudo -u "${DOMAIN}" ${WP_CLI} plugin install elementor --activate

# Step 7: Install & activate Hello Elementor theme
echo "[7/8] Installing Hello Elementor theme..."
sudo -u "${DOMAIN}" ${WP_CLI} theme install hello-elementor --activate

# Step 8: Basic WordPress settings
echo "[8/8] Configuring settings..."
sudo -u "${DOMAIN}" ${WP_CLI} option update permalink_structure '/%postname%/'
sudo -u "${DOMAIN}" ${WP_CLI} option update timezone_string 'UTC'
sudo -u "${DOMAIN}" ${WP_CLI} rewrite flush

# Remove default content
sudo -u "${DOMAIN}" ${WP_CLI} post delete 1 --force 2>/dev/null || true
sudo -u "${DOMAIN}" ${WP_CLI} post delete 2 --force 2>/dev/null || true
sudo -u "${DOMAIN}" ${WP_CLI} post delete 3 --force 2>/dev/null || true

echo "=== WordPress site created successfully: https://${DOMAIN} ==="
echo "Admin URL: https://${DOMAIN}/wp-admin"
echo "Username: ${WP_USER}"
