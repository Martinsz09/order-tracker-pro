# Usa PHP 8.2 com Apache
FROM php:8.2-apache

# Instala dependências do Laravel e PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    curl \
    && docker-php-ext-install pdo pdo_pgsql

# Ativa mod_rewrite (necessário pro Laravel)
RUN a2enmod rewrite

# Define diretório de trabalho
WORKDIR /var/www/html

# Copia Composer e instala dependências
COPY composer.lock composer.json ./
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copia todo o projeto
COPY . .

# Ajusta permissões para storage e cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Configura cache e clears do Laravel
RUN php artisan config:clear && \
    php artisan cache:clear && \
    php artisan route:clear && \
    php artisan view:clear

# Define variáveis de ambiente do Laravel (você também pode usar o .env do Render)
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

# Expõe porta padrão do Apache
EXPOSE 80

# Comando final para iniciar o Apache
CMD ["apache2-foreground"]
