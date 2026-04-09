#!/bin/sh
set -e

# 1. Performance Optimizations (Only if running as app/worker/scheduler)
# We want to ensure the app is cached for all Laravel processes
echo "📦 Optimizing Laravel for production..."
php artisan optimize:clear
php artisan optimize

# 2. Fix Volume Permissions (Must run as root)
echo "🔑 Fixing storage permissions..."
chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage

# 3. Handle Docker CMD (The "Command-Agnostic" Boot)
# This allows the same image to run as:
# - php-fpm (default)
# - php artisan queue:work
# - sh -c "while true; do ... done" (scheduler)
echo "🚀 Executing command: $@"
exec "$@"
