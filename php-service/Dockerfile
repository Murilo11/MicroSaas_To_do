FROM php:8.3-fpm

# Definir diretório de trabalho
WORKDIR /var/www

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    libonig-dev

# Limpar cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensões do PHP
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath sockets

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar arquivos existentes do projeto
COPY . /var/www

# Expor porta 9000 e iniciar server php-fpm
EXPOSE 9000
CMD ["php-fpm"]
