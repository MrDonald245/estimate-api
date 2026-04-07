FROM php:8.2-cli

# Установка зависимостей
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev \
    && docker-php-ext-install zip

# Установка Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Рабочая директория
WORKDIR /var/www/app

# Symfony server
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]