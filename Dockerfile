FROM php:8.2-apache

ENV DEBIAN_FRONTEND=noninteractive

# 1. Instalar dependências de sistema necessárias
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
        intl \
        bcmath \
    && a2enmod rewrite \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Instalar Composer mundialmente
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 3. Copiar arquivos do Composer primeiro (otimiza o cache do Docker)
COPY composer.json composer.lock* ./

# O parâmetro --no-scripts evita erros antes do código completo estar no container
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# 4. Copiar o restante do código da aplicação
COPY . .

# 5. Criar diretórios do Laravel e ajustar permissões de forma segura
RUN mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# 6. Configurar o Apache para apontar para a pasta /public do Laravel
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]
