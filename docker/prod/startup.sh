#!/usr/bin/env bash

set -e
set -u
set -o pipefail

artisan() {
    su -c "php artisan $*" -s /bin/bash devilbox
}

echo 'Fixing permissions...'
chown -R devilbox:devilbox /app/storage/logs/ || true

echo 'Exporting environment variables...'
env > /etc/environment

echo 'Rebuilding laravel cache...'
artisan optimize:clear
artisan event:clear

echo 'Starting cache...'
artisan dashboard:update-clicks-charts-cache
artisan dashboard:update-top-geo-cache

if [ "${APP_ENV}" != "local" ]; then
    artisan optimize
    artisan event:cache
fi

echo 'Linking storage path...'
artisan storage:link

echo 'Starting migrations...'
artisan migrate --no-interaction --force --isolated

echo "Notify about deploy"
artisan deploy:notify || true
