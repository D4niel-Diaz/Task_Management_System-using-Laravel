FROM composer:2 AS vendor
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

COPY . .
RUN composer dump-autoload --optimize --no-dev

FROM node:22-alpine AS assets
WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY resources ./resources
COPY public ./public
COPY vite.config.js ./
RUN npm run build

FROM php:8.4-cli-alpine AS app
WORKDIR /var/www/html

RUN apk add --no-cache bash icu-libs libzip oniguruma \
    && apk add --no-cache --virtual .build-deps icu-dev libzip-dev oniguruma-dev \
    && docker-php-ext-install intl mbstring pdo_mysql zip \
    && apk del .build-deps

COPY --from=vendor /app /var/www/html
COPY --from=assets /app/public/build /var/www/html/public/build
COPY docker/start.sh /usr/local/bin/start

RUN chmod +x /usr/local/bin/start \
    && mkdir -p storage/app/public storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

USER www-data

EXPOSE 8000

CMD ["start"]
