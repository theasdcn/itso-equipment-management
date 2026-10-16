FROM php:8.2-apache

# Install system dependencies and required PHP extensions for CodeIgniter 4
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    libsqlite3-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure intl \
    && docker-php-ext-install \
    intl \
    mysqli \
    pdo_mysql \
    zip \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configure PHP settings
RUN echo "memory_limit = 256M" >> /usr/local/etc/php/conf.d/docker-php-custom.ini \
    && echo "upload_max_filesize = 32M" >> /usr/local/etc/php/conf.d/docker-php-custom.ini \
    && echo "post_max_size = 32M" >> /usr/local/etc/php/conf.d/docker-php-custom.ini \
    && echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/docker-php-custom.ini

# Set Apache DocumentRoot to CodeIgniter's /public folder
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Allow .htaccess overrides in Apache
RUN sed -ri -e 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html/

# Install/verify composer dependencies (production mode)
RUN if [ ! -d "vendor" ] || [ ! -f "vendor/autoload.php" ]; then \
        composer install --no-dev --no-interaction --optimize-autoloader; \
    else \
        composer dump-autoload --optimize --no-dev; \
    fi

# Ensure writable and upload directories exist with full write permissions
RUN mkdir -p /var/www/html/writable/cache \
             /var/www/html/writable/logs \
             /var/www/html/writable/session \
             /var/www/html/writable/uploads \
             /var/www/html/public/uploads/profiles \
             /var/www/html/public/uploads/equipment \
    && chmod -R 777 /var/www/html/writable \
    && chmod -R 777 /var/www/html/public/uploads \
    && chown -R www-data:www-data /var/www/html/writable \
    && chown -R www-data:www-data /var/www/html/public/uploads

# Configure dynamic port handling for Render
RUN printf '#!/bin/bash\n\
PORT="${PORT:-80}"\n\
sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf\n\
sed -i "s/<VirtualHost \\*:80>/<VirtualHost \\*:$PORT>/g" /etc/apache2/sites-available/000-default.conf\n\
echo "=== Starting Apache on port $PORT ==="\n\
exec apache2-foreground\n' > /usr/local/bin/docker-entrypoint.sh \
    && chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

CMD ["/usr/local/bin/docker-entrypoint.sh"]
