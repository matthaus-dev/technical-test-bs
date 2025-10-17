FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN composer install --no-interaction --no-progress --prefer-dist

RUN chown -R www-data:www-data /var/www/html \
    && sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
    && printf '<Directory /var/www/html/public>\n    AllowOverride All\n    Require all granted\n</Directory>\n' > /etc/apache2/conf-available/override.conf \
    && a2enconf override \
    && a2enmod rewrite

EXPOSE 80

CMD ["apache2-foreground"]
