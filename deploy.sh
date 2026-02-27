#!/bin/bash
set -e

echo "🚀 Starting Deployment Process for Stunning Travel & Tours LTD..."

# 1. Enter Maintenance Mode
echo "🚧 Putting application into maintenance mode..."
php artisan down || true

# 2. Update code from Git
echo "📥 Pulling latest changes from repository..."
git pull origin main

# 3. Install/Update Dependencies
echo "📦 Installing Composer dependencies (Optimized)..."
# We disable timeout because of the massive Google API library
export COMPOSER_PROCESS_TIMEOUT=600
composer install --no-dev --optimize-autoloader --no-interaction

# 4. Database Migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# 5. Clear and Rebuild Caches
echo "⚡ Optimizing application performance..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 6. Set Directory Permissions (Standard VPS paths)
echo "🔐 Enforcing strict folder permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache || true

# 7. Exit Maintenance Mode
echo "✅ Deployment complete! Bringing application back online..."
php artisan up

echo "🌟 Stunning Travel & Tours is now LIVE and OPTIMIZED."
