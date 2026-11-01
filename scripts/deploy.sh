#!/usr/bin/env bash
# Applies the checked-out code on production.
# `make deploy` pulls master first, so this file is always the new version.
set -euo pipefail

APP_DIR=/var/www/sasha
BACKUP_DIR=/root/deploy-backups
BACKUPS_TO_KEEP=10
SITE_URL=https://mebelnyymaster.com.ua

step() {
    echo "==> $1"
}

cd "$APP_DIR"

step 'Backing up the database'
backup="$BACKUP_DIR/sasha-$(date +%Y%m%d-%H%M%S).sql.gz"
sudo bash -c "set -euo pipefail; umask 077; mkdir -p $BACKUP_DIR; mysqldump --single-transaction sasha | gzip > $backup"
sudo bash -c "ls -1t $BACKUP_DIR/*.sql.gz | tail -n +$((BACKUPS_TO_KEEP + 1)) | xargs -r rm -f"

step 'Installing packages'
composer install --no-dev --optimize-autoloader --no-interaction

step 'Running migrations'
php artisan migrate --force

step 'Rebuilding caches'
php artisan config:cache
sudo chown dev:www-data bootstrap/cache/config.php
chmod 640 bootstrap/cache/config.php
sudo -u www-data php artisan view:cache

step 'Reloading PHP'
sudo systemctl reload php8.5-fpm

step 'Checking the live site'
for path in / /kuhni; do
    curl -fsS --max-time 20 -o /dev/null "$SITE_URL$path"
done

step "Deployed $(git rev-parse --short HEAD)"
