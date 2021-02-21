FROM debian:stretch

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV PERFORMANCE_OPTIM false

RUN apt-get -qq update > /dev/null && DEBIAN_FRONTEND=noninteractive apt-get -qq -y --no-install-recommends install \
    supervisor \
    ca-certificates \
    nginx \
    git \
    wget \
    make \
    apt-transport-https > /dev/null &&\
    wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg &&\
    echo "deb https://packages.sury.org/php/ stretch main" > /etc/apt/sources.list.d/php.list &&\
    apt-get update -qq > /dev/null &&\
    DEBIAN_FRONTEND=noninteractive apt-get -qq -y --no-install-recommends install \
    unzip \
    php7.4 \
    php7.4-dev \
    php7.4-cli \
    php7.4-intl \
    php7.4-fpm \
    php7.4-xml \
    php7.4-mbstring \
    php7.4-curl \
    php7.4-pgsql \
    php-pear > /dev/null &&\
    echo $(pecl install xdebug | sed '$!d' | grep -o '".*"' | sed 's/"//g') >> /etc/php/7.4/fpm/php.ini &&\
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* &&\
    php -r "readfile('https://getcomposer.org/installer');" | php -- \
             --install-dir=/usr/local/bin \
             --filename=composer &&\
    echo "daemon off;" >> /etc/nginx/nginx.conf &&\
    mkdir -p /run/php &&\
    wget -qO- https://raw.githubusercontent.com/nvm-sh/nvm/v0.35.3/install.sh | bash &&\
    /bin/bash -c "source ~/.profile && nvm install node"

COPY rootfs /

WORKDIR /var/www

EXPOSE 80

CMD ["/entrypoint.sh"]
