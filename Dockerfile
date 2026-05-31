FROM php:8.2-apache

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    curl \
    libzip-dev \
    libxml2-dev \
    zlib1g-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring tokenizer xml ctype json zip \
    && docker-php-ext-enable pdo_pgsql

# Ativa mod_rewrite
RUN a2enmod rewrite

# Define diretório de trabalho
WORKDIR /var/www/html

# Copia Composer e instala
COPY composer.lock composer.json ./
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copia todo o projeto
COPY . .

# Permissões
RUN chown -R www-data:www-data storage bootstrap/cache

# Limpeza do Laravel
RUN php artisan config:clear && \
    php artisan cache:clear && \
    php artisan route:clear && \
    php artisan view:clear

# Variáveis de ambiente
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV APP_KEY=base64:B8o9JenRNMnUOt5Hq0Voofq+nCw0N3Mgr6Voh1FaZyQ=
ENV APP_URL=https://order-tracker-pro-ieok.onrender.com
ENV SESSION_DRIVER=cookie
ENV SESSION_LIFETIME=120
ENV SESSION_PATH=/
ENV SESSION_DOMAIN=order-tracker-pro-ieok.onrender.com
ENV SESSION_SECURE_COOKIE=true
ENV SESSION_HTTP_ONLY=true
ENV SESSION_SAME_SITE=lax

EXPOSE 80
CMD ["apache2-foreground"]
