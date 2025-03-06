FROM phpswoole/swoole:latest

# Defina o diretório de trabalho
WORKDIR /application

# Atualize o apt-get e instale as dependências necessárias
# libpq-dev é necessária para compilar a extensão pgsql do PHP
RUN apt-get update \
    && apt-get -y --no-install-recommends install \
        nano \
        libpq-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Instale a extensão pgsql
RUN docker-php-ext-install pgsql

# Instale a extensão xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug