#!/bin/sh
set -e

# 1. Performance Optimizations (Only if running as app/worker/scheduler)
if [ -f "vendor/autoload.php" ]; then
    echo "📦 Optimizing Laravel for production..."
    php artisan optimize:clear
    # Only run optimize if not in local env or if manually requested
    php artisan optimize
else
    echo "⚠️  vendor/autoload.php not found. Skipping optimization. Please run 'make setup'."
fi

# 2. Volume Permissions handled by Docker Compose user mapping
echo "🔑 Permissions already managed by host UID mapping..."

# 3. Handle Docker CMD (The "Command-Agnostic" Boot)
# This allows the same image to run as:
# - php-fpm (default)
# - php artisan queue:work
# - sh -c "while true; do ... done" (scheduler)
echo "🚀 Executing command: $@"
exec "$@"
