FROM php:8.4-cli

# Cài các extension PHP cần thiết cho Laravel + dompdf + MySQL
RUN apt-get update && apt-get install -y \
        git curl unzip \
        libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && rm -rf /var/lib/apt/lists/*

# Cài Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy toàn bộ code (bao gồm public/build đã build sẵn từ local)
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

# Railway cấp cổng qua biến $PORT, cần dùng shell form để biến được thay thế đúng
CMD php artisan config:cache \
    && php artisan route:cache \
    && php artisan migrate --force \
    && php artisan serve --host 0.0.0.0 --port ${PORT:-8080}
