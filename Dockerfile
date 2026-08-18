FROM php:8.4-apache

LABEL org.opencontainers.image.source=https://github.com/rahmadwidiansyah/klikrental

# --- 1. Install Dependencies Sistem ---
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    nodejs \
    npm \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    libzip-dev && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# --- 2. Install Ekstensi PHP (MySQL & Standar Laravel) ---
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    intl \
    zip

# --- 3. Konfigurasi Apache untuk Laravel ---
# PERBAIKAN 1: Tambahkan '=' di ENV
ENV APACHE_DOCUMENT_ROOT=/var/www/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf && \
    a2enmod rewrite

# --- 4. Install Composer ---
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# --- 5. Install Backend Dependencies ---
ENV COMPOSER_MEMORY_LIMIT=-1
COPY composer.json composer.lock ./
RUN --mount=type=cache,target=/tmp/composer-cache \
    COMPOSER_CACHE_DIR=/tmp/composer-cache composer install --no-dev --no-interaction --no-progress --no-scripts

# --- 6. Install Frontend Dependencies ---
COPY package.json package-lock.json .npmrc ./
RUN --mount=type=cache,target=/root/.npm npm ci

# --- 7. Copy Application and Build Frontend ---
COPY . .
RUN composer dump-autoload --no-dev --optimize --no-scripts && npm run build


# --- 8. Laravel Optimization --- 

# --- 9. Expose Port Apache ---
EXPOSE 80

# --- 10. Command Startup ---
# PERBAIKAN 2: Gunakan format array JSON [ "sh", "-c", "perintah..." ]
CMD ["sh", "-c", "php artisan config:cache && php artisan route:cache && php artisan view:cache && rm -rf public/storage && php artisan storage:link && chown -R www-data:www-data storage bootstrap/cache public/storage && chmod -R 775 storage bootstrap/cache public/storage && apache2-foreground"]