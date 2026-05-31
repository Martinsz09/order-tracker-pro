# Base PHP com Apache
FROM php:8.2-apache

# Evitar interação do apt
ENV DEBIAN_FRONTEND=noninteractive

# Atualizar e instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    libxml2-dev \
    libonig-dev \
    libpq-dev \
    build-essential \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        mbstring \
        xml \
        zip \
        tokenizer \
        ctype \
        json \
    && a2enmod rewrite \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Definir diretório de trabalho
WORKDIR /var/www/html

# Copiar arquivos do projeto
COPY . .

# Dar permissão para Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# Instalar dependências PHP via Composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Configurar Apache para apontar para /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]
