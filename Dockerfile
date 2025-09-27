FROM php:7.4-apache

# Install system dependencies and curl for health checks
RUN apt-get update && apt-get install -y \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions required by CodeIgniter
RUN docker-php-ext-install \
    mysqli \
    pdo_mysql \
    mbstring \
    xml \
    gd

# Enable Apache mod_rewrite for CodeIgniter URL rewriting
RUN a2enmod rewrite

# Configure Apache to allow .htaccess overrides
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Set proper ownership and permissions for CodeIgniter
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]