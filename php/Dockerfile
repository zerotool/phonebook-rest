FROM mileschou/phalcon:7.2-fpm

WORKDIR /var/www

RUN docker-php-ext-install pdo_mysql && docker-phalcon-install-devtools
RUN pecl install redis-4.0.1 && docker-php-ext-enable redis
RUN mkdir /var/log/phonebook && touch /var/log/phonebook/error.log \
 && chown www-data:www-data /var/log/phonebook/error.log
