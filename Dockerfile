FROM php:8.2-fpm

# Аргументы для пользователя
ARG UID=1000
ARG GID=1000

# Создаем пользователя
RUN groupadd -g $GID laravel \
    && useradd -m -u $UID -g $GID laravel

# Устанавливаем зависимости PHP
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# Устанавливаем Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Устанавливаем рабочую директорию
WORKDIR /var/www

# Копируем код приложения
COPY . .

# Устанавливаем зависимости Composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Даем права на storage и bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Запуск PHP-FPM
CMD ["php-fpm"]
