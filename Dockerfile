# Multi-stage build for optimized production image
FROM php:8.1-apache as base

# Set environment variables
ENV DEBIAN_FRONTEND=noninteractive
ENV APACHE_DOCUMENT_ROOT=/var/www/html
ENV PHP_MEMORY_LIMIT=256M
ENV PHP_MAX_EXECUTION_TIME=30
ENV PHP_UPLOAD_MAX_FILESIZE=10M
ENV PHP_POST_MAX_SIZE=10M

# Install system dependencies
RUN apt-get update && apt-get install -y \
    # Essential packages
    curl \
    wget \
    git \
    unzip \
    # Image processing
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libwebp-dev \
    # String processing
    libonig-dev \
    # XML processing
    libxml2-dev \
    # Compression
    libzip-dev \
    # Database clients
    default-mysql-client \
    # Process monitoring
    procps \
    # Cleanup
    && rm -rf /var/lib/apt/lists/* \
    && apt-get clean

# Configure and install PHP extensions
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp \
    && docker-php-ext-install -j$(nproc) \
    mysqli \
    pdo_mysql \
    mbstring \
    xml \
    gd \
    zip \
    opcache \
    bcmath

# Install and configure OPcache for better performance
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.interned_strings_buffer=8" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=4000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.revalidate_freq=2" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.fast_shutdown=1" >> /usr/local/etc/php/conf.d/opcache.ini

# Configure PHP settings
RUN echo "memory_limit=${PHP_MEMORY_LIMIT}" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "max_execution_time=${PHP_MAX_EXECUTION_TIME}" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "upload_max_filesize=${PHP_UPLOAD_MAX_FILESIZE}" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "post_max_size=${PHP_POST_MAX_SIZE}" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "date.timezone=UTC" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "log_errors=On" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "error_log=/var/log/php_errors.log" >> /usr/local/etc/php/conf.d/custom.ini

# Enable Apache modules
RUN a2enmod rewrite headers expires deflate ssl

# Configure Apache for better performance and security
RUN echo "ServerTokens Prod" >> /etc/apache2/conf-available/security.conf \
    && echo "ServerSignature Off" >> /etc/apache2/conf-available/security.conf \
    && a2enconf security

# Configure Apache document root and permissions
RUN sed -i "s|/var/www/html|${APACHE_DOCUMENT_ROOT}|g" /etc/apache2/sites-available/000-default.conf \
    && sed -i "s|/var/www/|${APACHE_DOCUMENT_ROOT}|g" /etc/apache2/apache2.conf \
    && sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Create necessary directories
RUN mkdir -p /var/www/html/Learning/application/logs \
    && mkdir -p /var/www/html/Learning/application/cache \
    && mkdir -p /var/www/html/uploads \
    && mkdir -p /var/log/apache2 \
    && touch /var/log/php_errors.log

# Set proper ownership and permissions
RUN chown -R www-data:www-data /var/www/html \
    && chown -R www-data:www-data /var/log/php_errors.log \
    && chmod -R 755 /var/www/html \
    && chmod -R 777 /var/www/html/Learning/application/logs \
    && chmod -R 777 /var/www/html/Learning/application/cache \
    && chmod -R 777 /var/www/html/uploads

# Add health check script
COPY --chown=www-data:www-data scripts/docker-health-check.sh /usr/local/bin/health-check
RUN chmod +x /usr/local/bin/health-check

# Add custom Apache configuration
COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf

# Production stage
FROM base as production

# Copy application files
COPY --chown=www-data:www-data Learning/ /var/www/html/

# Set production environment
ENV CI_ENV=production
ENV PHP_DISPLAY_ERRORS=Off
ENV PHP_LOG_ERRORS=On

# Disable OPcache revalidation in production
RUN sed -i 's/opcache.revalidate_freq=2/opcache.revalidate_freq=0/' /usr/local/etc/php/conf.d/opcache.ini

# Development stage
FROM base as development

# Install development tools
RUN apt-get update && apt-get install -y \
    vim \
    nano \
    htop \
    && rm -rf /var/lib/apt/lists/*

# Install Xdebug for development
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/xdebug.ini

# Set development environment
ENV CI_ENV=development
ENV PHP_DISPLAY_ERRORS=On
ENV PHP_LOG_ERRORS=On

# Enable OPcache revalidation in development
RUN sed -i 's/opcache.revalidate_freq=0/opcache.revalidate_freq=2/' /usr/local/etc/php/conf.d/opcache.ini

# Default to production stage
FROM production

# Add health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=60s --retries=3 \
    CMD /usr/local/bin/health-check

# Expose port 80
EXPOSE 80

# Use custom entrypoint for better initialization
COPY docker/docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["apache2-foreground"]