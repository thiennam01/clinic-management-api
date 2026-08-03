FROM php:8.5-cli

# Cài đặt các gói hệ thống và extension cần thiết cho Laravel (bao gồm thư viện cho Postgres: libpq-dev)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev

# Xóa cache apt
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Cài đặt PHP extensions (thêm pdo_pgsql và pgsql)
RUN docker-php-ext-install pdo_pgsql pgsql pdo_mysql mbstring exif pcntl bcmath gd

# Lấy Composer từ image chính thức
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Thiết lập thư mục làm việc
WORKDIR /var/www/html

# Copy toàn bộ mã nguồn hiện tại vào container
COPY . .

# Cài đặt các thư viện vendor qua composer (nếu chưa có sẵn)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Cấp quyền cho thư mục storage và bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Mở cổng 8000 để chạy artisan serve bên trong container
EXPOSE 8000

# Chạy lệnh khởi động server Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
