FROM php:8.2-apache

# PDO MySQL telepítése
RUN docker-php-ext-install pdo pdo_mysql

# Az app mappa másolása a konténerbe
COPY app/ /var/www/html/

# Győződj meg róla, hogy index.php van a gyökérben
