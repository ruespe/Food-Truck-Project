# =============================================================
# Stage 1 — Bootstrap de Laravel
# Usamos la imagen oficial de Composer para crear el proyecto
# desde cero. Esto genera composer.json, package.json,
# .env.example y toda la estructura de Laravel automáticamente.
# =============================================================
FROM composer:2 AS laravel-bootstrap

WORKDIR /app

RUN composer create-project laravel/laravel . \
    --no-interaction \
    --prefer-dist


# =============================================================
# Stage 2 — Compilar assets (Sass → CSS) con Node
# Tomamos package.json del proyecto recién creado en Stage 1.
# Node 22 es la versión Active LTS (soporte hasta Abril 2027).
# =============================================================
FROM node:22-alpine AS assets

WORKDIR /app

# package.json viene del proyecto Laravel ya generado
COPY --from=laravel-bootstrap /app/package*.json ./
COPY --from=laravel-bootstrap /app/vite.config.js ./
COPY --from=laravel-bootstrap /app/resources ./resources

# Instalar dependencias base + Bootstrap + Sass
# npm install en vez de npm ci porque create-project no genera package-lock.json
RUN npm install && npm install bootstrap sass

# Compilar assets con Vite
RUN npm run build


# =============================================================
# Stage 3 — Imagen final: PHP 8.2 + Apache (sirve Laravel)
# Node NO llega a esta imagen; solo los assets ya compilados.
# =============================================================
FROM php:8.2-apache

# --- Dependencias del sistema y extensiones PHP ---
# mbstring, gd, bcmath, exif, pcntl son requeridas por Laravel.
# Limpiamos la caché de apt al final para reducir el tamaño.
RUN apt-get update && apt-get install -y \
    git unzip curl \
    libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install \
    pdo pdo_mysql \
    mbstring \
    gd \
    bcmath \
    exif \
    pcntl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# --- Habilitar mod_rewrite (imprescindible para rutas de Laravel) ---
RUN a2enmod rewrite

# --- Instalar Composer ---
RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

# --- Proyecto Laravel base generado en Stage 1 ---
COPY --from=laravel-bootstrap /app ./

# --- Archivos personalizados del proyecto (sobreescriben los defaults) ---
# A medida que añadas controladores, vistas, rutas, etc.,
# estos COPY . . los incluirá automáticamente en la imagen.
COPY . .

# --- Assets compilados desde Stage 2 ---
# Solo traemos el resultado final, sin Node ni node_modules.
COPY --from=assets /app/public/build ./public/build

# --- Autoloader optimizado para producción ---
RUN composer dump-autoload --optimize

# --- APP_KEY: necesaria para que Laravel arranque ---
RUN php artisan key:generate --force

# --- Permisos ---
# Apache corre como www-data; storage y bootstrap/cache
# deben ser escribibles o Laravel falla al escribir logs/sesiones.
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Puerto 80 (Apache)
EXPOSE 80

CMD ["apache2-foreground"]