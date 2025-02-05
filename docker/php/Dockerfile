# Usa a imagem oficial do php-alpine como base
FROM php:8.2-fpm-alpine
LABEL maintainer="Vitor Nunes <vt.nunes1@gmail.com>"

# Copia as configurações do arquivo para dentro do container PHP
COPY default.ini "$PHP_INI_DIR/php.ini"

# Atualiza os índices de pacotes
RUN apk update && apk add --no-cache \
    wget \
    git \
    bash \
    curl \
    && rm -rf /var/cache/apk/*

# Copia o instalador de extensões do PHP
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# Instala extensões comuns do PHP
RUN install-php-extensions \
    pdo \
    pdo_mysql \
    opcache

# Instala o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer