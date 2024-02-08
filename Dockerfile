FROM public.ecr.aws/docker/library/php:8.3-fpm-alpine3.17

RUN apk add --no-cache --update $PHPIZE_DEPS bash git unzip msmtp jpegoptim \
    imagemagick-dev curl-dev libxml2-dev aspell-dev libxslt-dev libzip-dev zlib-dev gmp-dev libmemcached-dev icu-dev \
    libpng-dev libwebp-dev libjpeg-turbo-dev imagemagick libpng libjpeg-turbo libjpeg-turbo-utils \
    openssh-client npm yarn ruby ruby-dev libffi libffi-dev \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && npm install -g grunt-cli \
    && gem install ffi -- --enable-system-libffi && gem install --no-document compass \
    && CFLAGS="-I/usr/src/php" docker-php-ext-install bcmath curl dom gd opcache pcntl pdo pdo_mysql phar posix \
      pspell simplexml soap sockets xml xmlreader xmlwriter xsl zip gmp intl \
    && pecl install -o -f igbinary && docker-php-ext-enable igbinary \
    && ( \
        pecl install --nobuild memcached && \
        cd "$(pecl config-get temp_dir)/memcached" && \
        phpize && \
        ./configure --enable-memcached-igbinary && \
        make -j2 && \
        make install \
    ) && docker-php-ext-enable memcached \
    && pecl install -o -f xdebug && docker-php-ext-enable xdebug \
    && pecl install -o -f pcov && docker-php-ext-enable pcov \
    && git clone https://github.com/Imagick/imagick && cd imagick && \
      phpize && ./configure && make && make install && \
      cd ../ && rm -rf imagick && docker-php-ext-enable imagick \
    && git clone "https://github.com/tideways/php-xhprof-extension.git" && cd php-xhprof-extension && \
      phpize && ./configure && make && make install && docker-php-ext-enable tideways_xhprof