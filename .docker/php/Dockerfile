FROM php:7.4.3-fpm

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update -qq && \
    apt-get dist-upgrade -qqy && \
    apt-get install git -qqy