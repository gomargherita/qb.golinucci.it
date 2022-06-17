## Check scheduled with cron
- php artisan schedule:list
- sudo grep cron /var/log/syslog
- nano storage/logs/laravel.log

## Ogni volta che si cambia qualcosa nel .env in prod

https://beyondco.de/blog/laravel-caches-and-all-ways-to-clear-them
- php artisan cache:clear
- php artisan config:clear // Remove the configuration cache file
- php artisan config:cache // Create a cache file for faster configuration loading. This is for configuration cache. This command will clear the configuration cache before it creates
- php artisan route:clear

- php artisan optimize

## Check supervisor
- sudo supervisorctl status
https://laravel.com/docs/8.x/queues#starting-supervisor
vim /etc/supervisor/conf.d/laravel-worker.conf
sudo supervisorctl reread
sudo supervisorctl update

## Crontab

lista cron
# * * * * * cd /var/www/stagingqb.golinucci.it && php artisan schedule:run >> /dev/null 2>&1
# * * * * * cd /var/www/qb.golinucci.it && php artisan schedule:run >> /dev/null 2>&1
- sudo crontab -u www-data -l

modifica cron
- sudo crontab -u www-data -e