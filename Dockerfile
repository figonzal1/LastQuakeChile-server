FROM php:7.0-cli
RUN docker-php-ext-install mysqli
WORKDIR /lastquake_server
COPY . /lastquake_server
EXPOSE 80
CMD ["php","index.php"]