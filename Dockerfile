FROM php:8.3-apache

ENV DEBIAN_FRONTEND=noninteractive

# 1. Instalar apenas o essencial para rodar o Laravel + Postgres (Atualizado para PHP 8.3)
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libxml2-dev \
    libonig-dev \
    libpq-dev \
    libicu-dev \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        mbstring \
        xml \
        zip \
        intl \
        bcmath \
    && a2enmod rewrite \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# 2. Copia o projeto INTEIRO de uma vez (com a pasta vendor inclusa)
COPY . .

# 3. Permissões necessárias para o Laravel funcionar
RUN mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# 4. Aponta o Apache para a /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]
