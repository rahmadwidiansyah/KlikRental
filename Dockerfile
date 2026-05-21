FROM php:8.4-fpm

# Install dependencies sistem (Tambahkan nodejs & npm untuk build Vite)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    nodejs \
    npm

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
COPY . .

# Build Backend (Laravel) - optimasi autoloader untuk production
ENV COMPOSER_MEMORY_LIMIT=-1
RUN composer install --no-dev --optimize-autoloader

# Build Frontend (Vue/Inertia/Breeze) di dalam container agar konsisten
RUN npm install
RUN npm run build

# Ekspos port 9000 untuk berkomunikasi dengan Nginx
EXPOSE 9000

# Eksekusi Symlink dan Permission SAAT container start, baru jalankan php-fpm
# Ini akan mencegah error 403 Forbidden pada gambar/file upload
CMD sh -c "rm -rf public/storage && \
    php artisan storage:link && \
    chown -R www-data:www-data storage bootstrap/cache public/storage && \
    chmod -R 775 storage bootstrap/cache public/storage && \
    php-fpm"