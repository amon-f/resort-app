FROM node:22-alpine AS assets

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY resources ./resources
COPY public ./public
COPY vite.config.js ./
RUN npm run build

FROM serversideup/php:8.5-fpm-nginx

WORKDIR /var/www/html

COPY --chown=www-data:www-data . .
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

COPY --from=assets --chown=www-data:www-data /app/public/build ./public/build

RUN mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr \
    AUTORUN_ENABLED=true \
    HEALTHCHECK_PATH=/up \
    NGINX_HTTP_PORT=10000

EXPOSE 10000
