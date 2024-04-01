php artisan config:clear
php artisan route:clear
php artisan optimize:clear

# Migrações só ocorrem sob ENV local
export APP_ENV=local

php artisan migrate

export APP_ENV=production

php artisan config:cache
php artisan route:cache
php artisan optimize
php artisan storage:link

php-fpm
