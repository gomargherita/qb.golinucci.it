# Golinucci QB

Golinucci QB App. Powered by Laravel.

## Installation

Required PHP modules:

0. php >= 8.0
1. ext-curl
2. ext-dom
3. ext-exif
4. ext-fileinfo
5. ext-filter
6. ext-gd
7. ext-iconv
8. ext-json
9. ext-libxml
10. ext-mbstring
11. ext-openssl
12. ext-pcre
13. ext-phar
14. ext-simplexml
15. ext-tokenizer
16. ext-xml
17. ext-xmlreader
18. ext-xmlwriter
19. ext-zip
20. ext-zlib
21. lib-pcre

To perform a fresh start, do the following:

1. `php artisan db:wipe`
2. `php artisan migrate`
3. `php artisan db:seed`
4. `php artisan med:importer --file=database-giugno-settembre.csv`
5. `php artisan med:importer --file=database-ottobre-giugno.csv`
6. `php artisan user:update --id=1` to reset the Super Admin password for **admin@example.com**

## Check scheduled with cron

- php artisan schedule:list
- sudo grep cron /var/log/syslog
- nano storage/logs/laravel.log

## Ogni volta che si cambia qualcosa nel .env in prod

[https://beyondco.de/blog/laravel-caches-and-all-ways-to-clear-them]

- php artisan cache:clear
- php artisan config:clear // Remove the configuration cache file
- php artisan config:cache // Create a cache file for faster configuration loading. This is for configuration cache. This command will clear the configuration cache before it creates
- php artisan route:clear

- php artisan optimize

## Check supervisor

- sudo supervisorctl status
- [https://laravel.com/docs/8.x/queues#starting-supervisor]
- vim /etc/supervisor/conf.d/laravel-worker.conf
- sudo supervisorctl reread
- sudo supervisorctl update

## Crontab

```text
lista cron
# * * * * * cd /var/www/stagingqb.golinucci.it && php artisan schedule:run >> /dev/null 2>&1
# * * * * * cd /var/www/qb.golinucci.it && php artisan schedule:run >> /dev/null 2>&1
- sudo crontab -u www-data -l

modifica cron
- sudo crontab -u www-data -e
```
