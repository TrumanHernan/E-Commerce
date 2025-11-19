#!/bin/bash
set -e

echo "Starting Laravel E-Commerce application..."

# Asegurar que los directorios existen y tienen los permisos correctos
mkdir -p /var/log/supervisor /var/log/php-fpm /var/log/nginx
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Establecer permisos correctos
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Esperar si hay variables de entorno que necesitan procesarse
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:placeholder" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Limpiar caché de configuración
echo "Clearing configuration cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimizar para producción
if [ "$APP_ENV" = "production" ]; then
    echo "Optimizing for production..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

# Ejecutar migraciones
echo "Running migrations..."
php artisan migrate --force --no-interaction

# Crear storage link si no existe
if [ ! -L "public/storage" ]; then
    echo "Creating storage link..."
    php artisan storage:link
fi

echo "Application ready!"

# Ejecutar comando pasado al contenedor
exec "$@"
