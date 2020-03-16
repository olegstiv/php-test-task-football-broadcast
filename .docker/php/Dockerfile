FROM php:7.4.3-fpm

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update -qq && \
    apt-get dist-upgrade -qqy && \
    apt-get install git -qqy && \
    apt-get install -y libonig-dev libpq-dev && \
    apt install mcrypt -qqy && \
    docker-php-ext-install mbstring && \
    docker-php-ext-install pdo_mysql
