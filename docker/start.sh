#!/bin/sh
set -e

echo "==> Configurando php-fpm para socket unix..."
sed -i 's|listen = 9000|listen = /var/run/php-fpm.sock|' /usr/local/etc/php-fpm.d/www.conf
sed -i 's|;listen.owner = www-data|listen.owner = www-data|' /usr/local/etc/php-fpm.d/www.conf
sed -i 's|;listen.group = www-data|listen.group = www-data|' /usr/local/etc/php-fpm.d/www.conf
sed -i 's|;listen.mode = 0660|listen.mode = 0660|' /usr/local/etc/php-fpm.d/www.conf

echo "==> Otimizando autoload do Composer..."
composer dump-autoload --optimize --no-dev --no-scripts 2>/dev/null || true

echo "==> Publicando config e limpando caches antigos..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "==> Rodando migrations..."
php artisan migrate --force

echo "==> Cacheando config, rotas e views para produção..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Iniciando serviços (nginx + php-fpm) via supervisor..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
