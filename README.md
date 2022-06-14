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

## Crontab

lista cron
- crontab -l

modifica cron
- crontab -e