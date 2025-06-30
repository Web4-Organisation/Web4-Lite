# Use an official PHP image with Apache
FROM php:8.0-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    curl \
    libzip-dev \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mysqli zip curl json mbstring exif

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html/

# Install Composer dependencies for addons
RUN cd sys/addons && composer install --ignore-platform-reqs --no-dev --prefer-dist

# Configure Apache
COPY .htaccess /var/www/html/.htaccess
COPY apache-custom.conf /etc/apache2/conf-available/custom-config.conf
RUN a2enconf custom-config
RUN a2enmod rewrite
# Ensure correct ownership for web files
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
