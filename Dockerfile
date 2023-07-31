# Use the official PHP image as the base image
FROM php:8.2-apache

# Set the working directory
WORKDIR /jwt-auth-system-backend

# Copy composer.lock and composer.json to the working directory
COPY composer.lock composer.json ./

# Install PHP extensions and dependencies
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libonig-dev \
    zip \
    unzip \
    && docker-php-ext-install \
    intl \
    mbstring \
    pdo_mysql \
    && pecl install apcu \
    && docker-php-ext-enable apcu

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the entire project to the working directory
COPY . .

# Install Symfony dependencies
RUN composer install --prefer-dist --no-scripts --no-progress --no-suggest

# Set up Apache configuration
COPY .docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Expose port 8000
EXPOSE 8000

# Set the command to start the Symfony server
CMD ["php", "bin/console", "server:run", "0.0.0.0:8000"]