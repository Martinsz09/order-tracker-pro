FROM php:8.2-apache

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    libxml2-dev \
    libonig-dev \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring xml zip \
    && a2enmod rewrite

# Composer direto da imagem oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copia tudo primeiro (IMPORTANTE)
COPY . .

# Instala dependências PHP (AGORA funciona porque o código já existe)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Permissões Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# Apache apontando para /public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]
