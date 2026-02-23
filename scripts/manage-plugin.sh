#!/bin/bash
# Manage WordPress plugins via WP-CLI
# Usage: ./manage-plugin.sh <domain> <action> <plugin_slug>

set -e

DOMAIN=$1
ACTION=$2
PLUGIN=$3

if [ -z "$DOMAIN" ] || [ -z "$ACTION" ]; then
    echo "Usage: $0 <domain> <install|activate|deactivate|delete|list> [plugin_slug]"
    exit 1
fi

SITE_DIR="/home/${DOMAIN}/htdocs/${DOMAIN}"
WP_CLI="/usr/local/bin/wp"

cd "${SITE_DIR}"

case "$ACTION" in
    "install")
        [ -z "$PLUGIN" ] && echo "Plugin slug required" && exit 1
        sudo -u "${DOMAIN}" ${WP_CLI} plugin install "${PLUGIN}" --activate
        echo "Plugin installed and activated: ${PLUGIN}"
        ;;
    "activate")
        [ -z "$PLUGIN" ] && echo "Plugin slug required" && exit 1
        sudo -u "${DOMAIN}" ${WP_CLI} plugin activate "${PLUGIN}"
        echo "Plugin activated: ${PLUGIN}"
        ;;
    "deactivate")
        [ -z "$PLUGIN" ] && echo "Plugin slug required" && exit 1
        sudo -u "${DOMAIN}" ${WP_CLI} plugin deactivate "${PLUGIN}"
        echo "Plugin deactivated: ${PLUGIN}"
        ;;
    "delete")
        [ -z "$PLUGIN" ] && echo "Plugin slug required" && exit 1
        sudo -u "${DOMAIN}" ${WP_CLI} plugin deactivate "${PLUGIN}" 2>/dev/null || true
        sudo -u "${DOMAIN}" ${WP_CLI} plugin delete "${PLUGIN}"
        echo "Plugin deleted: ${PLUGIN}"
        ;;
    "list")
        sudo -u "${DOMAIN}" ${WP_CLI} plugin list --format=json
        ;;
    *)
        echo "Invalid action: ${ACTION}"
        exit 1
        ;;
esac
