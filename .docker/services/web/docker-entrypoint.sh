#!/bin/bash
set -e

# --- Log File Initialization ---
touch /var/www/.docker/logs/nginx-error.log
chown nginx:nginx /var/www/.docker/logs/nginx-error.log
chmod 664 /var/www/.docker/logs/nginx-error.log

# --- Directory Preparation ---
mkdir -p /home/nginx/.npm

# --- Targeted Permissions Management ---
chown -R nginx:nginx /home/nginx/.npm
chmod -R 775 /home/nginx/.npm


# --- Start Container Process ---
# Pass execution to the CMD defined in the Dockerfile (e.g., nginx)
exec "$@"
