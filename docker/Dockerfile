FROM php:7.3-apache-buster

RUN rm -rf /etc/localtime \
    && ln -s /usr/share/zoneinfo/Europe/Zurich /etc/localtime

# install mssql
RUN apt-get update; \
    ACCEPT_EULA=Y apt-get install -y --no-install-recommends \
    software-properties-common \
    locales \
    gnupg \
    curl \
    wget; \
    echo "en_US.UTF-8 UTF-8" > /etc/locale.gen; \
    locale-gen; \
    curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add -; \
    add-apt-repository "$(wget -qO- https://packages.microsoft.com/config/ubuntu/16.04/prod.list)"; \
    apt-get update && \
    ACCEPT_EULA=Y apt-get install -y mssql-tools \
    unixodbc \
    unixodbc-dev; \
    pecl install sqlsrv; \
    docker-php-ext-enable sqlsrv

# install php memcached extension
RUN apt-get update && apt-get install -y libmemcached-dev zlib1g-dev \
    && pecl install memcached \
    && docker-php-ext-enable memcached

# copy php.ini-development to php.ini
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

# global PHP configuration changes
RUN sed -i \
    -e 's~^;date.timezone =$~date.timezone = Europe/Zurich~g' \
    -e 's~^display_errors = Off$~display_errors = On~g' \
    -e 's~^display_startup_errors = Off$~display_startup_errors = On~g' \
    /usr/local/etc/php/php.ini

COPY index.php /var/www/html/

# copy entrypoint script
COPY entrypoint.sh /usr/local/bin/

ENTRYPOINT ["entrypoint.sh"]

CMD ["apache2-foreground"]