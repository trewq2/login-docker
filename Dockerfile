FROM php:8.2-apache

# PDO MySQL telepítése
RUN docker-php-ext-install pdo pdo_mysql
