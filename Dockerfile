FROM php:8.2-apache

ENV DEBIAN_FRONTEND=noninteractive

# Instalar dependências de sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    libxml2-dev \
    libonig-dev \
    libpq-dev \
    build-essential \
    pkg-config \
    libicu-dev \
    zlib1g-dev \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        mbstring \
        xml \
        zip \
        tokenizer \
        ctype \
        json \
        intl \
    && a2enmod rewrite \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

# Permissões corretas
RUN chown -R www-data:www-data storage bootstrap/cache

# Instalar dependências do PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Configurar Apache para /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]
