FROM php:8.2-apache

# PDO MySQL telepítése
RUN docker-php-ext-install pdo pdo_mysql

# Másoljuk a projektet a konténerbe
COPY app/ /var/www/html/

# Jogosultságok beállítása
RUN chown -R www-data:www-data /var/www/html
