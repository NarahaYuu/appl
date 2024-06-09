# Dockerfile

# Gunakan image resmi PHP dengan Apache
FROM php:8.1-apache

# Install dependencies yang diperlukan
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    && docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Salin file konfigurasi Apache
COPY apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Set working directory
WORKDIR /var/www/html

# Salin file aplikasi ke dalam container
COPY . /var/www/html

# Install dependencies
RUN composer install

# Set proper permissions
RUN chmod -R 777 /var/www/html
RUN chmod -R 777 /var/www/html/storage
RUN chmod -R 777 /var/www/html/bootstrap/cache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80

# Jalankan perintah ini setiap kali container di-start
CMD ["apache2-foreground"]

