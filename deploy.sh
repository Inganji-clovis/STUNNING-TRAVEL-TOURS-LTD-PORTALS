#!/bin/bash

# ==========================================
# STUNNING TRAVEL & TOURS LTD 
# AUTOMATED DEPLOYMENT SCRIPT (Nindohost/AOS)
# ==========================================

# Stop execution if any command fails
set -e

echo "🚀 Starting Deployment Process..."

# 1. Enter Maintenance Mode
# This prevents users from breaking the app during migrations
echo "🔒 Enabling Maintenance Mode..."
php artisan down || true

# 2. Pull Latest Code from Repository
echo "⬇️ Pulling latest changes from Git..."
git pull origin main

# 3. Install/Update Composer Dependencies
# Optimized for Google Drive API libraries (forces 600s timeout)
echo "📦 Installing Composer Dependencies..."
COMPOSER_PROCESS_TIMEOUT=600 composer install --no-interaction --prefer-dist --optimize-autoloader

# 4. Clear and Rebuild Laravel Caches
echo "🧹 Clearing and Rebuilding Caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "⚡ Optimizing Application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Run Database Migrations
echo "🗄️ Running Database Migrations..."
php artisan migrate --force

# 6. Apply Correct Server Permissions (Optional depending on host)
# echo "🔐 Setting Directory Permissions..."
# chown -R www-data:www-data storage bootstrap/cache
# chmod -R 775 storage bootstrap/cache

# 7. Exit Maintenance Mode
echo "🔓 Disabling Maintenance Mode..."
php artisan up

echo "✅ DEPLOYMENT SUCCESSFUL! Stunning Travel is live! 🇷🇼"
