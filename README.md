## Check scheduled with cron
- php artisan schedule:list
- sudo grep cron /var/log/syslog

## Check supervisor
- sudo supervisorctl status
https://laravel.com/docs/8.x/queues#starting-supervisor