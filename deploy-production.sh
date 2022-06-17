#!/bin/bash

# Move to project folder
cd /var/www/qb.golinucci.it || exit

# Set application offline
php artisan down

# Update git project
git pull origin $(git rev-parse --abbrev-ref HEAD)

# Run pending migrations:
php artisan migrate

# Remove cached data
sudo rm -rf storage/framework/cache/data/*
sudo rm -rf storage/framework/sessions/*
sudo rm -rf storage/framework/views/*
# php artisan optimize:clear

# Update composer class autoloader
/usr/local/bin/composer dump-autoload

# Update assets
npm update && npm run prod

# Fix permissions
sudo chgrp -R www-data storage bootstrap/cache
sudo chmod -R ug+rwx storage bootstrap/cache

# Set application online
php artisan up

# Restart queue worker
php artisan queue:restart