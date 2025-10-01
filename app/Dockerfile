FROM php:8.2-apache-bookworm
ENV DEBIAN_FRONTEND=noninteractive

# PHP + Postgres
RUN set -eux; \
    apt-get -o Acquire::ForceIPv4=true -o Acquire::AllowReleaseInfoChange=true update; \
    apt-get install -y --no-install-recommends libpq-dev ca-certificates; \
    update-ca-certificates; \
    docker-php-ext-install -j"$(nproc)" pdo_pgsql; \
    a2enmod rewrite; \
    rm -rf /var/lib/apt/lists/*

# Переключаем DocumentRoot на /public во ВСЕХ нужных местах
RUN set -eux; \
  sed -ri 's#DocumentRoot /var/www/html#DocumentRoot /var/www/html/public#' /etc/apache2/sites-available/000-default.conf; \
  if ! grep -q '<Directory /var/www/html/public>' /etc/apache2/sites-available/000-default.conf; then \
    printf '%s\n' \
    '<Directory /var/www/html/public>' \
    '  Options -Indexes +FollowSymLinks' \
    '  AllowOverride All' \
    '  Require all granted' \
    '</Directory>' \
    >> /etc/apache2/sites-available/000-default.conf; \
  fi; \
  sed -ri 's#<Directory /var/www/>#<Directory /var/www/>#' /etc/apache2/apache2.conf

# Папка для загрузок и права
RUN mkdir -p /var/www/html/public/uploads \
 && chown -R www-data:www-data /var/www/html \
 && chmod -R 755 /var/www/html
