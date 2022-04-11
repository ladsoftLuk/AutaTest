FROM php:8.0-fpm-alpine as linux_dependencies
ENV PY_PIP_VERSION=9.0.1-r1
ENV SUPERVISOR_VERSION=3.3.1

# Install modules


RUN apk upgrade --update && apk --no-cache add \
        $PHPIZE_DEPS \
        freetype-dev \
        libjpeg-turbo-dev \
        libpng-dev \
        icu-dev \
        libpq \
        curl-dev \
        oniguruma-dev \
        unzip \
        postgresql-dev \
        rabbitmq-c \
        rabbitmq-c-dev \
        wget \
        curl \
        py-pip  

RUN wget https://getcomposer.org/download/2.0.9/composer.phar \
    && mv composer.phar /usr/bin/composer && chmod +x /usr/bin/composer

RUN pip install supervisor

FROM linux_dependencies AS php_installation

# Extract PHP source
# Create directory for amqp extension
# Download AMQP master branch files to extension directory
# Install amqp extension using built-in docker binary
RUN docker-php-source extract \
    && mkdir /usr/src/php/ext/amqp \
    && curl -L https://github.com/php-amqp/php-amqp/archive/master.tar.gz | tar -xzC /usr/src/php/ext/amqp --strip-components=1

RUN docker-php-ext-install \
	bcmath \
    intl \
    opcache \
    mbstring \
    amqp

FROM php_installation AS php_extentions_installation

#        amqp-1.10.2 \
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        -j$(nproc) gd \
        pdo \
        pdo_pgsql \
    && pecl install \
        redis-5.3.2 \
        igbinary-3.2.1 \
    && docker-php-ext-enable \
        redis \
        amqp \
        igbinary

RUN docker-php-source delete

RUN mkdir -p /var/log/supervisor
#COPY php/sup/worker.conf /etc/supervisor/conf.d/supervisord.conf
COPY php/sup/worker.conf /etc/supervisord.conf
WORKDIR /var/www/project

COPY php/sup/start.sh /

#CMD ["php-fpm"]
#CMD ["/start.sh"]
CMD ["/usr/bin/supervisord"]