#!/bin/bash
# Delete a WordPress site and its database
# Usage: ./delete-wordpress-site.sh <domain> <db_name>

set -e

DOMAIN=$1
DB_NAME=$2

if [ -z "$DOMAIN" ]; then
    echo "Usage: $0 <domain> [db_name]"
    exit 1
fi

CLP_CLI="/usr/bin/clpctl"

echo "=== Deleting WordPress site: ${DOMAIN} ==="

# Delete database if provided
if [ -n "$DB_NAME" ]; then
    echo "[1/2] Deleting database: ${DB_NAME}..."
    ${CLP_CLI} db:delete --dbName="${DB_NAME}" --force 2>/dev/null || echo "Database not found or already deleted"
fi

# Delete site from CloudPanel
echo "[2/2] Deleting site from CloudPanel..."
${CLP_CLI} site:delete --domainName="${DOMAIN}" --force 2>/dev/null || echo "Site not found or already deleted"

# Cleanup any remaining files
if [ -d "/home/${DOMAIN}" ]; then
    rm -rf "/home/${DOMAIN}"
    echo "Removed site directory: /home/${DOMAIN}"
fi

echo "=== Site deleted: ${DOMAIN} ==="
