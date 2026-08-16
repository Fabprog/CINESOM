#!/bin/sh
set -e

echo "==> Rodando migrations..."
php artisan migrate --force

echo "==> Limpando caches antigos..."
php artisan config:clear
php artisan cache:clear || true
php artisan view:clear
php artisan route:clear

echo "==> Cacheando config, rotas e views para producao..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Iniciando servicos (nginx + php-fpm) via supervisor..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
