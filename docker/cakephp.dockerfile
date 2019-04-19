FROM php:7.3-apache

# install cakephp prerequisites 
RUN requirements="libmcrypt-dev g++ libicu-dev libmcrypt4 libicu57 libxml2-dev" \
     && apt-get update && apt-get install -y $requirements

RUN docker-php-ext-install pdo_mysql mbstring intl simplexml

RUN a2enmod rewrite
RUN a2enmod ssl

# install composer
RUN curl -sSL https://getcomposer.org/installer | php \
     && mv composer.phar /usr/local/bin/composer \
     && apt-get update \
     && apt-get install -y zlib1g-dev git libzip4 libzip-dev unzip \
     && docker-php-ext-install zip \
     && apt-get purge -y --auto-remove zlib1g-dev

# install node
RUN curl -sL https://deb.nodesource.com/setup_10.x | bash \
     && apt-get -y install nodejs

# link cakephp app to default apache directory
RUN rm -R /var/www/html && ln -s /app/webroot /var/www/html