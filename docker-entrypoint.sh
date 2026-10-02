#!/bin/bash
set -e

# Render passes dynamic port in $PORT, default to 80 if unset
PORT="${PORT:-80}"

sed -i "s/80/${PORT}/g" /etc/apache2/ports.conf /etc/apache2/sites-available/*.conf

# Optimize Laravel
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run apache
exec apache2-foreground
