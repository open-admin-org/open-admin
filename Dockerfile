FROM php:8.3-fpm

# Install system dependencies + pdo extensions
RUN apt-get update \
 && apt-get install -y \
      git curl zip unzip libzip-dev libpng-dev libonig-dev libxml2-dev libsqlite3-dev libpq-dev \
 && docker-php-ext-install \
      pdo_mysql \
      pdo_sqlite \
      pdo_pgsql \
      zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .
CMD ["sh", "-c", "composer install && vendor/bin/pest"]
