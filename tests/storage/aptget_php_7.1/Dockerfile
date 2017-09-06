FROM ubuntu:16.04
WORKDIR /opt/xdebug-manager

RUN apt-get update && apt-get install -y \
        php7.1-cli \
        php7.1-fpm \
    --no-install-recommends && rm -r /var/lib/apt/lists/*

EXPOSE 9000
CMD ["php-fpm"]
