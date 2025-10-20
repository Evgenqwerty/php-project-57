# Стадия сборки фронтенда
FROM node:18 as frontend

WORKDIR /app
COPY package.json package-lock.json vite.config.js ./
COPY resources ./resources

RUN npm install --force && \
    npm run build

# Основной образ
FROM php:8.2-fpm

# Установка системных зависимостей
RUN apt-get update && \
    apt-get install -y \
        git \
        unzip \
        libpq-dev \
        libzip-dev && \
    docker-php-ext-install pdo pdo_pgsql zip && \
    rm -rf /var/lib/apt/lists/*

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app
COPY . .

# Копируем собранные ассеты
COPY --from=frontend /app/public/build /app/public/build

# Установка PHP зависимостей
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Настройка прав
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache && \
    chmod -R 775 /app/storage /app/bootstrap/cache

EXPOSE 10000

CMD ["bash", "-c", "php artisan migrate --force && php artisan optimize && php artisan serve --host=0.0.0.0 --port=$PORT"]
