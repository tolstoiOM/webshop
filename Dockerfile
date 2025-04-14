FROM php:8.2-apache

# Installiere PHP-Erweiterungen
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Optional: Apache mod_rewrite aktivieren
RUN a2enmod rewrite