#!/usr/bin/env sh
set -eu

cd /var/www

if [ ! -f .env ]; then
    cp .env.example .env
fi

if [ ! -f vendor/autoload.php ]; then
    composer install --no-interaction --prefer-dist
fi

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

if ! grep -q '^APP_KEY=base64:' .env; then
    php artisan key:generate --force
fi

exec "$@"
