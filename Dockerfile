FROM php:8.2-apache

# Install system dependencies, required PHP extensions for CodeIgniter 4, and MariaDB server
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    mariadb-server \
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

# Ensure writable and upload directories exist with full permissions
RUN mkdir -p /var/www/html/writable/cache \
             /var/www/html/writable/logs \
             /var/www/html/writable/session \
             /var/www/html/writable/uploads \
             /var/www/html/public/uploads/profiles \
             /var/www/html/public/uploads/equipment \
    && chmod -R 777 /var/www/html/writable \
    && chmod -R 777 /var/www/html/public/uploads

# Setup MariaDB runtime and entrypoint script
RUN printf '#!/bin/bash\n\
PORT="${PORT:-80}"\n\
\n\
# Initialize MariaDB directory if not present\n\
mkdir -p /var/run/mysqld /var/lib/mysql\n\
chown -R mysql:mysql /var/run/mysqld /var/lib/mysql\n\
if [ ! -d "/var/lib/mysql/mysql" ]; then\n\
    mariadb-install-db --user=mysql --datadir=/var/lib/mysql > /dev/null 2>&1\n\
fi\n\
\n\
# Start MariaDB service\n\
service mariadb start\n\
\n\
# Wait a moment for MariaDB to become ready\n\
for i in {1..30}; do\n\
    if mysqladmin ping --silent; then\n\
        break\n\
    fi\n\
    sleep 1\n\
done\n\
\n\
# Setup database and import SQL dump if needed\n\
mysql -u root -e "CREATE DATABASE IF NOT EXISTS itso_equipment_db;"\n\
TABLE_COUNT=$(mysql -u root -N -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '\''itso_equipment_db'\'';" 2>/dev/null || echo 0)\n\
if [ "$TABLE_COUNT" -eq 0 ] && [ -f "/var/www/html/itso_equipment_db.sql" ]; then\n\
    echo "Importing itso_equipment_db.sql..."\n\
    mysql -u root itso_equipment_db < /var/www/html/itso_equipment_db.sql\n\
    echo "Database imported successfully!"\n\
fi\n\
\n\
# Ensure root has access from localhost and 127.0.0.1\n\
mysql -u root -e "GRANT ALL PRIVILEGES ON *.* TO '\''root'\''@'\''localhost'\'' WITH GRANT OPTION; GRANT ALL PRIVILEGES ON *.* TO '\''root'\''@'\''127.0.0.1'\'' WITH GRANT OPTION; FLUSH PRIVILEGES;" 2>/dev/null || true\n\
\n\
# Configure Apache port dynamically for Render\n\
sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf\n\
sed -i "s/<VirtualHost \\*:80>/<VirtualHost \\*:$PORT>/g" /etc/apache2/sites-available/000-default.conf\n\
echo "Starting Apache on port $PORT..."\n\
exec apache2-foreground\n' > /usr/local/bin/docker-entrypoint.sh \
    && chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

CMD ["/usr/local/bin/docker-entrypoint.sh"]
