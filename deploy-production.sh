#!/bin/bash

# Move to project folder
cd /var/www/qb.golinucci.it || exit

# Set application offline
sudo -u www-data php artisan down

# Update git project
git checkout master
git pull origin $(git rev-parse --abbrev-ref HEAD)

# Fix permissions
sudo chown -R www-data:www-data /var/www/qb.golinucci.it

# Run pending migrations:
sudo -u www-data php artisan migrate

# Remove cached data
sudo rm -rf storage/framework/cache/data/*
sudo rm -rf storage/framework/sessions/*
sudo rm -rf storage/framework/views/*
# php artisan optimize:clear

# Update composer class autoloader
sudo -u www-data /usr/local/bin/composer dump-autoload

# Update assets
npm update && npm run prod

# Set application online
sudo -u www-data php artisan up

# Restart queue worker
sudo -u www-data php artisan queue:restart