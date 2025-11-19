# Multi-stage build para optimizar el tamaño de la imagen
FROM php:8.2-fpm-alpine AS base

# Instalar dependencias del sistema
RUN apk add --no-cache \
    bash \
    curl \
    git \
    zip \
    unzip \
    nginx \
    supervisor \
    sqlite \
    nodejs \
    npm

# Instalar extensiones de PHP requeridas por Laravel
RUN apk add --no-cache \
    $PHPIZE_DEPS \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    postgresql-dev \
    mysql-dev \
    sqlite-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    pdo_sqlite \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    intl \
    opcache

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configurar directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos de composer
COPY composer.json composer.lock ./

# Instalar dependencias de PHP (sin scripts y sin dev)
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copiar archivos de package.json
COPY package*.json ./

# Instalar dependencias de Node.js (incluyendo devDependencies para el build)
RUN npm ci

# Copiar el resto del código
COPY . .

# Generar autoloader optimizado
RUN composer dump-autoload --optimize --no-dev

# Compilar assets con Vite
RUN npm run build

# Limpiar node_modules para reducir tamaño de imagen (no hay dependencias de producción)
RUN rm -rf node_modules

# Crear directorios necesarios y establecer permisos
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Copiar configuración de nginx
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf

# Copiar configuración de PHP-FPM
COPY docker/php/php.ini /usr/local/etc/php/conf.d/laravel.ini
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

# Copiar configuración de supervisor
COPY docker/supervisor/supervisord.conf /etc/supervisord.conf

# Copiar script de entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Exponer puerto 8080 (Render usa este puerto por defecto)
EXPOSE 8080

# Usar supervisor para manejar nginx, php-fpm y queue worker
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
