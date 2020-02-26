FROM phpdockerio/php74-fpm:latest
WORKDIR "/app"

ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update \
    && apt-get -y --no-install-recommends install  php7.4-mysql php-redis php7.4-sqlite3 \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*