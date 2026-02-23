#!/bin/bash
# Manage WordPress themes via WP-CLI
# Usage: ./manage-theme.sh <domain> <action> <theme_slug>

set -e

DOMAIN=$1
ACTION=$2
THEME=$3

if [ -z "$DOMAIN" ] || [ -z "$ACTION" ]; then
    echo "Usage: $0 <domain> <install|activate|delete|list> [theme_slug]"
    exit 1
fi

SITE_DIR="/home/${DOMAIN}/htdocs/${DOMAIN}"
WP_CLI="/usr/local/bin/wp"

cd "${SITE_DIR}"

case "$ACTION" in
    "install")
        [ -z "$THEME" ] && echo "Theme slug required" && exit 1
        sudo -u "${DOMAIN}" ${WP_CLI} theme install "${THEME}" --activate
        echo "Theme installed and activated: ${THEME}"
        ;;
    "activate")
        [ -z "$THEME" ] && echo "Theme slug required" && exit 1
        sudo -u "${DOMAIN}" ${WP_CLI} theme activate "${THEME}"
        echo "Theme activated: ${THEME}"
        ;;
    "delete")
        [ -z "$THEME" ] && echo "Theme slug required" && exit 1
        sudo -u "${DOMAIN}" ${WP_CLI} theme delete "${THEME}"
        echo "Theme deleted: ${THEME}"
        ;;
    "list")
        sudo -u "${DOMAIN}" ${WP_CLI} theme list --format=json
        ;;
    *)
        echo "Invalid action: ${ACTION}"
        exit 1
        ;;
esac
