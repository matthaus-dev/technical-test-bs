FROM composer:2 AS vendor

WORKDIR /app

# Copy backend sources and install PHP dependencies
COPY backend/ /app/
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

FROM php:8.4-apache

# Install system deps used by PHP extensions (if any)
RUN apt-get update \
  && apt-get install -y --no-install-recommends libzip-dev zip unzip git \
  && docker-php-ext-install zip pdo pdo_mysql \
  && rm -rf /var/lib/apt/lists/*

# Copy application from vendor stage
COPY --from=vendor /app /var/www/html

# Enable apache rewrite and set document root to /var/www/html/public
RUN a2enmod rewrite
RUN sed -i 's#/var/www/html#/var/www/html/public#g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]
