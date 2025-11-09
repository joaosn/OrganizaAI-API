FROM php:8.2-apache

# Instalar extensões PHP necessárias
RUN apt-get update && apt-get install -y     libzip-dev     unzip     libonig-dev     libicu-dev     libpq-dev     libxml2-dev     && docker-php-ext-install pdo_mysql mysqli pdo_pgsql intl opcache     && docker-php-ext-enable opcache

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar Apache para usar index.php como padrão
RUN echo "DirectoryIndex index.php" > /etc/apache2/conf-available/dir.conf
RUN a2enconf dir

# Habilitar mod_rewrite para URLs amigáveis
RUN a2enmod rewrite

# Configurar o diretório de trabalho
WORKDIR /var/www/html

# Expor a porta 80
EXPOSE 80
