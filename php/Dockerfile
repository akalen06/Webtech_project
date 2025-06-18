# Gebruik de officiële PHP 8.2 FPM image als basis
FROM php:8.2-fpm

# Installeer de mysqli en pdo_mysql extensies
# Deze commando's zijn nodig om PHP te laten communiceren met een MySQL/MariaDB database
RUN docker-php-ext-install mysqli pdo pdo_mysql
