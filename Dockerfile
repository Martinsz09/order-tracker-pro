# Usar PHP + Apache
FROM php:8.2-apache

ENV DEBIAN_FRONTEND=noninteractive

# Instalar dependências do Laravel
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    libxml2-dev \
    libonig-dev \
    libpq-dev \
    build-essential \
    && docker-php-ext-install pdo pdo_pgsql mbstring xml zip tokenizer ctype json \
    && a2enmod rewrite

# Composer oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configurar diretório do projeto
WORKDIR /var/www/html

# Copiar todos os arquivos do projeto para dentro do container
COPY . .

# Instalar dependências PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Permissões corretas para Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# Configurar Apache para apontar para /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]
