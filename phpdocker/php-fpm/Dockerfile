FROM phpdockerio/php56-fpm:latest

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install  php5-mysql php5-igbinary php5-xdebug \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

WORKDIR "/var/www/samaprk-dev"

#COPY "../../system/application/config/config.php.docker" "system/application/config/config.php"
#COPY "../../system/application/config/database.php.docker" "system/application/config/database.php"