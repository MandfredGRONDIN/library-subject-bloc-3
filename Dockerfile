FROM php:8.2.19-apache

# Installer les extensions PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql && \
    docker-php-ext-enable pdo_mysql && \
    a2enmod rewrite

# Supprimer les fichiers HTML par défaut
RUN rm -rf /var/www/html/*

# Créer un utilisateur non-root
RUN useradd -u 1000 -m -s /bin/bash appuser && \
    chown -R appuser:www-data /var/www/html && \
    chmod -R 750 /var/www/html

USER appuser

WORKDIR /var/www/html
