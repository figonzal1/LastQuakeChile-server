FROM php:7.4-apache
ENV TZ="America/Santiago"
LABEL org.opencontainers.image.source = "https://github.com/figonzal/LastQuakeChile-server"
RUN apt-get clean \ 
&& apt-get update \ 
&& apt-get install libzip-dev libapache2-mod-security2 -y \ 
&& apt-get update \
&& docker-php-source extract \ 
&& docker-php-ext-install zip pdo_mysql \ 
&& docker-php-source delete \ 
&& a2enmod rewrite \ 
&& service apache2 restart \ 
&& apt-get clean

# Copiar carpeta projecto
COPY ./src/ /var/www/src
COPY *.json .env /var/www/
COPY ./public/figonzal.cl /var/www/html/

#Configuraciones apache
COPY ./api-files/apache2.conf /etc/apache2/apache2.conf
COPY ./api-files/000-figonzal.conf /etc/apache2/sites-available/000-figonzal.conf
COPY ./api-files/server.pem /etc/ssl/certs/server.pem
COPY ./api-files/server-key.pem /etc/ssl/certs/server-key.pem

RUN a2ensite 000-figonzal.conf \ 
&& a2enmod ssl \ 
&& service apache2 restart

# Archivo de configuracion PHP
COPY ./api-files/php-production.ini "$PHP_INI_DIR/php.ini"
#COPY ./api-files/php-development.ini "$PHP_INI_DIR/php.ini"

RUN usermod -u 1000 www-data \
&& chown -R www-data:www-data /var/www/ \
&& php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
&& php composer-setup.php --filename=composer --install-dir=/usr/local/bin \
&& php -r "unlink('composer-setup.php');" \
&& cd /var/www/ && composer install

EXPOSE 443