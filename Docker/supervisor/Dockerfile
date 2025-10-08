FROM php:7.3-cli

RUN apt-get update && apt-get install -y \
    supervisor \
    unzip \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN mkdir -p /var/log/supervisor /etc/supervisor/conf.d

COPY Docker/supervisor/supervisord.conf /etc/supervisord.conf
COPY Docker/supervisor/supervisor-mailworker.conf /etc/supervisor/conf.d/

CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisord.conf"]
