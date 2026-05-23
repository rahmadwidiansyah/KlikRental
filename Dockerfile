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
ENV APACHE_DOCUMENT_ROOT /var/www/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf && \
    a2enmod rewrite

# --- 4. Install Composer ---
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www
COPY . .

# --- 5. Build Backend ---
ENV COMPOSER_MEMORY_LIMIT=-1
RUN composer install --no-dev --optimize-autoloader

# --- 6. Build Frontend ---
RUN npm install
RUN npm run build

# --- 7. Laravel Optimization ---
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# --- 8. Expose Port Apache ---
EXPOSE 80

# --- 9. Command Startup ---
CMD sh -c "rm -rf public/storage && \
    php artisan storage:link && \
    chown -R www-data:www-data storage bootstrap/cache public/storage && \
    chmod -R 775 storage bootstrap/cache public/storage && \
    apache2-foreground"