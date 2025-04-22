FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
      apt-utils \
      libpq-dev \
      libpng-dev \
      libzip-dev \
      zip unzip \
      git && \
      docker-php-ext-install pdo pdo_pgsql bcmath gd zip && \
      apt-get clean && \
      rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY ./_docker/app/php.ini /usr/local/etc/php/conf.d/php.ini

# Install composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- \
    --filename=composer \
    --install-dir=/usr/local/bin

WORKDIR /var/www

# Копируем файлы проекта (после установки зависимостей, чтобы кэшировать слои)
COPY . /var/www

# Установка зависимостей Composer
RUN composer install --no-dev --optimize-autoloader

# Запускаем artisan migrations (если необходимо)
# RUN php artisan migrate --force

# Очистка кэша Laravel (если необходимо)
# RUN php artisan config:cache
# RUN php artisan route:cache
# RUN php artisan view:cache

EXPOSE 9000

ENTRYPOINT ["php-fpm"]
