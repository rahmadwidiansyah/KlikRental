FROM php:8.4-fpm

# Install dependencies sistem
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev

# Bersihkan cache apt agar image lebih ringan
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install ekstensi PHP yang dibutuhkan KlikRental (MySQL)
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd

# Ambil Composer versi terbaru
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy seluruh file project ke dalam container
# (Pastikan folder public/build hasil Vite dari PC lokal ikut ter-copy)
COPY . .

# Bypass limit memori composer & jalankan instalasi
ENV COMPOSER_MEMORY_LIMIT=-1
RUN composer install --no-dev --optimize-autoloader

# Atur hak akses folder agar bisa ditulis oleh Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Ekspos port 9000 untuk berkomunikasi dengan Nginx
EXPOSE 9000

# Jalankan PHP-FPM secara langsung, tanpa script tambahan yang memberatkan
CMD ["php-fpm"]