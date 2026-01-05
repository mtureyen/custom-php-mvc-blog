# =============================================================================
# PHP-FPM Container Configuration
# =============================================================================

# 1. Base Image
# We use the official PHP 8.3 image with FPM (FastCGI Process Manager).
# FPM is required because Nginx handles the HTTP traffic and hands off
# PHP execution to this container via the FastCGI protocol.
FROM php:8.3-fpm

# 2. Install Dependencies
# The standard PHP image is minimal. We must explicitly install the
# PDO MySQL extensions so our application can talk to the database.
RUN docker-php-ext-install pdo pdo_mysql

# 3. Working Directory
# Set the default directory inside the container.
# This ensures that commands like 'composer install' run in the correct folder.
WORKDIR /var/www/html