#!/usr/bin/env bash
# Script de build para Render

set -e

echo "🔧 Instalando dependencias de Composer..."
composer install --no-dev --optimize-autoloader --no-scripts

echo "🔑 Generando Application Key..."
php artisan key:generate --force

echo "📁 Creando directorios necesarios..."
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

echo "🔓 Configurando permisos..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chmod 664 storage/logs/laravel.log 2>/dev/null || touch storage/logs/laravel.log && chmod 664 storage/logs/laravel.log

echo "🔗 Creando symlink de storage..."
php artisan storage:link --force

echo "📊 Ejecutando migraciones..."
php artisan migrate --force

echo "✅ Build completado exitosamente!"
