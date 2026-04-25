FROM php:8.2-apache

# Enable Apache rewrite (useful for many PHP apps)
RUN a2enmod rewrite

# Copy app files into Apache web root
COPY . /var/www/html/

# Set permissions (optional but common)
RUN chown -R www-data:www-data /var/www/html