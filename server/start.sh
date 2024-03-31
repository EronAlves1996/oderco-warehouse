php artisan migrate

# Migrações só ocorrem sob ENV local
export APP_ENV=production

php artisan config:clear
php artisan config:cache

php artisan route:clear
php artisan route:cache

php artisan optimize

php-fpm
