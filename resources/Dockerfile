#ARG PHP_EXTENSIONS="sqlite3 pdo_mysql pdo_sqlite"
ARG PHP_VERSION

FROM thecodingmachine/php:${PHP_VERSION}-v4-cli

USER root
RUN apt-get update \
    && apt-get install -y --no-install-recommends php${PHP_VERSION}-phpdbg

#COPY composer.json /usr/src/app
#RUN pwd
#RUN composer install
