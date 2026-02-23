#!/bin/bash
# Backup a WordPress site (full, database, or files only)
# Usage: ./backup-wordpress-site.sh <domain> <type> <output_path>

set -e

DOMAIN=$1
TYPE=$2
OUTPUT_PATH=$3

if [ -z "$DOMAIN" ] || [ -z "$TYPE" ] || [ -z "$OUTPUT_PATH" ]; then
    echo "Usage: $0 <domain> <full|database|files> <output_path>"
    exit 1
fi

SITE_DIR="/home/${DOMAIN}/htdocs/${DOMAIN}"
WP_CLI="/usr/local/bin/wp"
TEMP_DIR=$(mktemp -d)

echo "=== Backing up ${DOMAIN} (${TYPE}) ==="

case "$TYPE" in
    "full")
        echo "Creating full backup..."
        # Export database
        cd "${SITE_DIR}"
        sudo -u "${DOMAIN}" ${WP_CLI} db export "${TEMP_DIR}/database.sql"
        # Archive everything
        tar -czf "${OUTPUT_PATH}" -C "/home/${DOMAIN}/htdocs" "${DOMAIN}" -C "${TEMP_DIR}" "database.sql"
        ;;
    "database")
        echo "Creating database backup..."
        cd "${SITE_DIR}"
        sudo -u "${DOMAIN}" ${WP_CLI} db export "${TEMP_DIR}/database.sql"
        tar -czf "${OUTPUT_PATH}" -C "${TEMP_DIR}" "database.sql"
        ;;
    "files")
        echo "Creating files backup..."
        tar -czf "${OUTPUT_PATH}" -C "/home/${DOMAIN}/htdocs" "${DOMAIN}"
        ;;
    *)
        echo "Invalid backup type. Use: full, database, or files"
        rm -rf "${TEMP_DIR}"
        exit 1
        ;;
esac

# Cleanup
rm -rf "${TEMP_DIR}"

FILE_SIZE=$(stat -c%s "${OUTPUT_PATH}" 2>/dev/null || echo "0")
echo "=== Backup completed: ${OUTPUT_PATH} (${FILE_SIZE} bytes) ==="
