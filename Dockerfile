FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    bash \
    postgresql-client \
    $PHPIZE_DEPS \
    libpq-dev \
    linux-headers \
    supervisor

RUN docker-php-ext-install pdo_pgsql sockets bcmath \
    && docker-php-ext-enable sockets \
    && pecl install redis \
    && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY composer.json composer.lock ./

RUN composer install --no-interaction --prefer-dist --no-autoloader

COPY . .

RUN composer dump-autoload --optimize

RUN chown -R www-data:www-data /var/www

COPY docker/supervisord.conf /etc/supervisor/supervisord.conf
COPY docker/laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf

EXPOSE 9000

CMD ["php-fpm"]