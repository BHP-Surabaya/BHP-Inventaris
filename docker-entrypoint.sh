#!/bin/bash
set -e

# Render / Cloud passes dynamic port in $PORT, default to 80 if unset
PORT="${PORT:-80}"

sed -i "s/80/${PORT}/g" /etc/apache2/ports.conf /etc/apache2/sites-available/*.conf

# Pastikan folder penyimpanan dan permission selalu valid
mkdir -p /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/framework/cache \
         /var/www/html/storage/logs
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Pastikan symlink storage publik selalu tersedia
php artisan storage:link --force || true

# Jalankan migrasi database otomatis jika AUTORUN_MIGRATIONS diaktifkan
if [ "$AUTORUN_MIGRATIONS" = "true" ]; then
    echo "Running automated database migrations..."
    php artisan migrate --force
fi

# Optimize Laravel caching untuk performa tinggi produksi
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Jalankan Apache Web Server
exec apache2-foreground

