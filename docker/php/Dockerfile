# =============================================================================
# PHP-FPM Container Configuration
# =============================================================================

# 1. Base Image
# We use the official PHP 8.3 image with FPM (FastCGI Process Manager).
FROM php:8.3-fpm

# 2. Install System Dependencies & PHP Extensions
# We install 'unzip' and 'git' because Composer requires them to extract packages.
# 'pdo_mysql' is essential for connecting to your MySQL database.
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql

# 3. Install Composer
# We copy the official Composer binary directly from the latest composer image.
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Working Directory
# Set the default directory inside the container.
WORKDIR /var/www/html

# 5. Environment Variables
# Allows Composer to run as root, which is required during the Docker build process.
ENV COMPOSER_ALLOW_SUPERUSER=1

# 6. Copy Project Files
# We copy everything from the build context (Root) into the WORKDIR.
COPY . .

# 7. Install Dependencies
# This automatically runs 'composer install' during the docker build.
# It creates the 'vendor' folder and generates the autoloader.
RUN composer install --no-interaction --optimize-autoloader

# 8. Permissions & Uploads Folder
# We create the directory first. 
# Then we change the owner to 'www-data' (the user PHP-FPM runs as).
# Finally, we set permissions so the webserver can write files.
RUN mkdir -p public/uploads && \
    chown -R www-data:www-data public/uploads && \
    chmod -R 775 public/uploads