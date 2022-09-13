FROM ubuntu:20.04

LABEL maintainer="ArtAlexM"

ARG WWWGROUP
ARG WWWUSER

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND noninteractive
ENV TZ=UTC
ENV NODE_VERSION=18

# Packages install
RUN apt-get update \
    && apt-get install -y gnupg gosu curl ca-certificates zip unzip git supervisor  \
    sqlite3 libcap2-bin libpng-dev mc make librdkafka-dev postgresql-client

# Add php repository
RUN mkdir -p ~/.gnupg \
    && chmod 600 ~/.gnupg \
    && echo "disable-ipv6" >> ~/.gnupg/dirmngr.conf \
    && apt-key adv --homedir ~/.gnupg --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys E5267A6C \
    && apt-key adv --homedir ~/.gnupg --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys C300EE8C \
    && echo "deb http://ppa.launchpad.net/ondrej/php/ubuntu focal main" > /etc/apt/sources.list.d/ppa_ondrej_php.list

# Install php
RUN apt-get update \
    && apt-get install -y php8.1-cli php8.1-dev

# Install php extensions
RUN apt-get install -y php8.1-gd php8.1-pgsql \
       php8.1-curl php8.1-mbstring \
       php8.1-xml php8.1-zip php8.1-iconv \
       php8.1-intl php8.1-readline \
       php8.1-redis php8.1-xdebug

# Install kafka php extension
RUN curl -O https://pear.php.net/go-pear.phar
RUN php go-pear.phar
RUN pecl channel-update pecl.php.net && pecl install rdkafka

# Install php composer
RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

# Install Nodjs
RUN curl -sL https://deb.nodesource.com/setup_$NODE_VERSION.x | bash - \
    && apt-get install -y nodejs

# Install Yarn
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list \
    && apt-get update \
    && apt-get install -y yarn

# Clean instalation
RUN apt-get -y autoremove \
    && apt-get list --installed -a '*-dev' | grep "^[^\ ]*/" -o | sed 's/\///' | xargs -I '{}' apt-get remove -y '{}' \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN groupadd --force -g $WWWGROUP micro
RUN useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u $WWWUSER micro

COPY start-container /usr/local/bin/start-container

COPY php.ini /etc/php/8.1/cli/conf.d/99-micro.ini
COPY xdebug.ini /etc/php/8.1/cli/conf.d/99-xdebug.ini
COPY rdkafka.ini /etc/php/8.1/cli/conf.d/99-rdkafka.ini

RUN chmod +x /usr/local/bin/start-container

ENTRYPOINT ["start-container"]
