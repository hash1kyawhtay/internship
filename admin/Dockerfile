# Use official PHP with Apache
FROM php:8.2-apache

# Install PDO and MySQL driver
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli

# Copy backend code
COPY . /var/www/html/

# Set proper permissions (optional)
RUN mkdir -p /var/www/html/uploads \
    && chown -R www-data:www-data /var/www/html/uploads

# Expose port 80
EXPOSE 80
# 