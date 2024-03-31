FROM php:8.2-fpm-alpine as base

# INSTALL MYSQL EXTENSION
RUN docker-php-ext-install mysqli pdo pdo_mysql

# CONFIGURE BUILD STAGE
FROM base as build

# COMPOSER
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# UPDATE APK
RUN apk update

# GIT
RUN apk add git

# INSTALL ZIP EXTENSION
RUN apk add \
        libzip-dev \
        zip
RUN docker-php-ext-install zip

# COPY APPLICATION
WORKDIR /var/www
COPY . .

# INSTALL DEPS
RUN composer install

# TEST STAGE
FROM build as test

# INSTALL GD EXTENSION
RUN apk add \
        freetype-dev \
        libpng-dev
RUN docker-php-ext-configure gd --with-freetype
RUN docker-php-ext-install -j$(nproc) gd

# CONFIGURATIONS FOR TEST
ENV DB_CONNECTION mysql

# INTERNAL IP FOR HOST MACHINE FOR DOCKER
ENV DB_HOST 172.17.0.1
ENV DB_PORT 3306
ENV DB_DATABASE oderco
ENV DB_USERNAME root
ENV DB_PASSWORD root
ENV APP_ENV local
ENV APP_DEBUG true
ENV APP_KEY base64:4JFY6GLzEcpmULIrp/5B+gVQOgSg6JmkkeSEjwUDNKA=
ENV LOG_LEVEL debug
ENV LOG_CHANNEL stack
ENV LOG_DEPRECATIONS_CHANNEL null
ENV FILESYSTEM_DISK public

# CACHE CONFIG
RUN php artisan config:cache

# MIGRATION
RUN php artisan migrate:refresh

# TEST
RUN php artisan test

# AFTER RUNNING TESTS AND INSTALLING DEPS, WE GONNA MOUNT THE APP FOR PROD
FROM base as prod
WORKDIR /var/www
COPY --from=build /var/www /var/www
RUN addgroup -g 1000 -S www && \
    adduser -u 1000 -S www -G www
USER www
COPY --chown=www:www . /var/www
EXPOSE 9000
CMD ["sh", "start.sh"]
