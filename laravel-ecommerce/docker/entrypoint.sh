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

# Limpiar caché de configuración (solo archivos locales, no requiere DB)
echo "Clearing configuration cache..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Función para verificar conexión a la base de datos
wait_for_db() {
    echo "Waiting for database connection..."
    max_tries=30
    count=0

    until php artisan db:show --database=pgsql >/dev/null 2>&1 || [ $count -eq $max_tries ]; do
        count=$((count + 1))
        echo "Attempt $count/$max_tries: Database not ready yet..."
        sleep 2
    done

    if [ $count -eq $max_tries ]; then
        echo "WARNING: Could not connect to database after $max_tries attempts"
        return 1
    fi

    echo "Database connection established!"
    return 0
}

# Intentar conectar a la base de datos
if wait_for_db; then
    # Limpiar cache de base de datos solo si la conexión funciona
    echo "Clearing database cache..."
    php artisan cache:clear || true

    # Ejecutar migraciones
    echo "Running migrations..."
    php artisan migrate --force --no-interaction || echo "WARNING: Migrations failed"

    # Ejecutar seeders
    # Usar variable de entorno SKIP_SEEDERS=true para omitir seeders en despliegues futuros
    if [ "$SKIP_SEEDERS" != "true" ]; then
        echo "Checking if database needs seeding..."
        # Intentar contar productos usando SQL directo
        PRODUCT_COUNT=$(php artisan db:show --database=pgsql --json 2>/dev/null | grep -o '"tables":' || echo "")

        # Si la tabla productos existe, verificar si está vacía
        TABLE_EXISTS=$(php -r "
            try {
                \$pdo = new PDO('pgsql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
                \$result = \$pdo->query('SELECT COUNT(*) as count FROM productos');
                echo \$result->fetch()['count'];
            } catch (Exception \$e) {
                echo '0';
            }
        " 2>/dev/null || echo "0")

        if [ "$TABLE_EXISTS" = "0" ] || [ "$RUN_SEEDERS" = "true" ]; then
            echo "Database is empty or RUN_SEEDERS=true. Running seeders..."
            php artisan db:seed --force && echo "✓ Seeders completed successfully!" || echo "WARNING: Seeding failed"
        else
            echo "Database already has $TABLE_EXISTS products, skipping seeders"
            echo "Set SKIP_SEEDERS=false or RUN_SEEDERS=true to force seeding"
        fi
    else
        echo "SKIP_SEEDERS=true, skipping database seeding"
    fi
else
    echo "WARNING: Skipping database operations due to connection issues"
fi

# Optimizar para producción (no requiere DB)
if [ "$APP_ENV" = "production" ]; then
    echo "Optimizing for production..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

# Crear storage link si no existe
if [ ! -L "public/storage" ]; then
    echo "Creating storage link..."
    php artisan storage:link || true
fi

echo "Application ready!"

# Ejecutar comando pasado al contenedor
exec "$@"
