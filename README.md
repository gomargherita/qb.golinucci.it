## Check scheduled with cron
- php artisan schedule:list
- sudo grep cron /var/log/syslog
- nano storage/logs/laravel.log


## Check supervisor
- sudo supervisorctl status
https://laravel.com/docs/8.x/queues#starting-supervisor