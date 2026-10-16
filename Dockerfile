FROM php:8.2-apache

# Install system dependencies, required PHP extensions for CodeIgniter 4, and MariaDB server
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    mariadb-server \
    mariadb-client \
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
    && echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/docker-php-custom.ini \
    && echo "pdo_mysql.default_socket=/var/run/mysqld/mysqld.sock" >> /usr/local/etc/php/conf.d/docker-php-custom.ini \
    && echo "mysqli.default_socket=/var/run/mysqld/mysqld.sock" >> /usr/local/etc/php/conf.d/docker-php-custom.ini

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

# Ensure writable and upload directories exist with full permissions
RUN mkdir -p /var/www/html/writable/cache \
             /var/www/html/writable/logs \
             /var/www/html/writable/session \
             /var/www/html/writable/uploads \
             /var/www/html/public/uploads/profiles \
             /var/www/html/public/uploads/equipment \
    && chmod -R 777 /var/www/html/writable \
    && chmod -R 777 /var/www/html/public/uploads

# Pre-initialize MariaDB and import database during build
RUN mkdir -p /var/run/mysqld /var/lib/mysql \
    && chown -R mysql:mysql /var/run/mysqld /var/lib/mysql \
    && mariadb-install-db --user=mysql --datadir=/var/lib/mysql \
    && /usr/bin/mysqld_safe --datadir=/var/lib/mysql --skip-networking=0 & \
    for i in {1..30}; do mysqladmin ping --silent && break || sleep 1; done \
    && mysql -u root -e "CREATE DATABASE IF NOT EXISTS itso_equipment_db;" \
    && mysql -u root itso_equipment_db < /var/www/html/itso_equipment_db.sql \
    && mysql -u root -e "CREATE USER IF NOT EXISTS 'itso_db_user'@'%' IDENTIFIED BY 'itso_password_123';" \
    && mysql -u root -e "GRANT ALL PRIVILEGES ON *.* TO 'itso_db_user'@'%' WITH GRANT OPTION;" \
    && mysql -u root -e "CREATE USER IF NOT EXISTS 'itso_db_user'@'localhost' IDENTIFIED BY 'itso_password_123';" \
    && mysql -u root -e "GRANT ALL PRIVILEGES ON *.* TO 'itso_db_user'@'localhost' WITH GRANT OPTION;" \
    && mysql -u root -e "CREATE USER IF NOT EXISTS 'root'@'127.0.0.1' IDENTIFIED BY '';" \
    && mysql -u root -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'127.0.0.1' WITH GRANT OPTION;" \
    && mysql -u root -e "FLUSH PRIVILEGES;" \
    && mysqladmin -u root shutdown

# Create robust startup script
RUN printf '#!/bin/bash\n\
PORT="${PORT:-80}"\n\
\n\
echo "=== Starting MariaDB server ==="\n\
mkdir -p /var/run/mysqld /var/lib/mysql\n\
chown -R mysql:mysql /var/run/mysqld /var/lib/mysql\n\
/usr/bin/mysqld_safe --datadir=/var/lib/mysql --skip-networking=0 &\n\
\n\
echo "=== Waiting for MariaDB to be ready ==="\n\
for i in {1..30}; do\n\
    if mysqladmin ping --silent; then\n\
        echo "MariaDB is up and responding!"\n\
        break\n\
    fi\n\
    sleep 1\n\
done\n\
\n\
# Configure Apache port dynamically for Render\n\
sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf\n\
sed -i "s/<VirtualHost \\*:80>/<VirtualHost \\*:$PORT>/g" /etc/apache2/sites-available/000-default.conf\n\
\n\
echo "=== Starting Apache on port $PORT ==="\n\
exec apache2-foreground\n' > /usr/local/bin/docker-entrypoint.sh \
    && chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

CMD ["/usr/local/bin/docker-entrypoint.sh"]
