#!/bin/bash

php8.1 composer.phar dump-autoload
php8.1 artisan cache:clear
php8.1 artisan config:clear
php8.1 artisan debugbar:clear
php8.1 artisan event:clear
php8.1 artisan optimize:clear
php8.1 artisan route:clear
php8.1 artisan twig:clean
php8.1 artisan view:clear
