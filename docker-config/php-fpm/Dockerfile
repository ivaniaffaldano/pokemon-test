FROM php:7.2-fpm

ARG xdebug=1

# Workaround for java install error
# https://bugs.debian.org/cgi-bin/bugreport.cgi?bug=863199
RUN mkdir -p /usr/share/man/man1

# Install selected extensions and other stuff

RUN apt-get update && apt-get install -y \
    apt-utils \
    libfreetype6-dev \
    libjpeg-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    imagemagick \
    libmagickwand-dev \
    libmcrypt-dev \
    libbz2-dev \
    zlib1g \
    unzip \
    libyaml-dev \
    libxml2-dev \
    libicu-dev \
    libxslt-dev \
    git \
    default-jre

RUN docker-php-ext-configure gd \
    --with-gd \
    --with-freetype-dir=/usr/include/ \
    --with-png-dir=/usr/include/ \
    --with-jpeg-dir=/usr/include/ && \
  NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) && \
  docker-php-ext-install -j${NPROC} gd

RUN docker-php-ext-install -j$(nproc) iconv pdo_mysql bcmath bz2 intl mbstring soap xsl zip opcache

RUN pecl install redis \
    && pecl install imagick \
    && pecl install mcrypt-1.0.1 \
    && docker-php-ext-enable redis imagick mcrypt

# Install XDebug
RUN pecl install xdebug-2.6.0 \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.idekey = PHPSTORM" >> /usr/local/etc/php/conf.d/xdebug.ini.disabled \
    && echo "xdebug.default_enable = 1" >> /usr/local/etc/php/conf.d/xdebug.ini.disabled \
    && echo "xdebug.remote_enable = 1" >> /usr/local/etc/php/conf.d/xdebug.ini.disabled \
    && echo "xdebug.remote_autostart = 1" >> /usr/local/etc/php/conf.d/xdebug.ini.disabled \
    && echo "xdebug.remote_connect_back = 0" >> /usr/local/etc/php/conf.d/xdebug.ini.disabled \
    && echo "xdebug.profiler_enable = 1" >> /usr/local/etc/php/conf.d/xdebug.ini.disabled \
    && echo "xdebug.remote_log='/tmp/xdebug.log'" >> /usr/local/etc/php/conf.d/xdebug.ini.disabled \
    && echo "xdebug.remote_host = 127.0.0.1" >> /usr/local/etc/php/conf.d/xdebug.ini.disabled \
    && echo "xdebug.remote_port = 9009" >> /usr/local/etc/php/conf.d/xdebug.ini.disabled

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN mkdir -p /usr/local/bin \
    && curl -LsS https://symfony.com/installer -o /usr/local/bin/symfony \
    && chmod a+x /usr/local/bin/symfony
    
RUN mkdir /var/symfony && chmod -R 777 /var/symfony

COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh
ENTRYPOINT ["docker-entrypoint.sh"]
WORKDIR "/application"
CMD ["php-fpm"]
