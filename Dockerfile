FROM php:8.4-fpm

ARG USER_ID
ARG GROUP_ID

RUN groupadd -g $GID laravel \
    && useradd -m -u $UID -g $GID laravel

# Устанавливаем зависимости и расширения PHP
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# Устанавливаем Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install

CMD ["php-fpm"]
