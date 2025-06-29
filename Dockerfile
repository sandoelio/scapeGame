# Etapa 1: Build de assets com Node
FROM node:20 AS nodebuilder

# Define diretório de trabalho para Node
WORKDIR /app

# Copia arquivos de dependência
COPY package*.json vite.config.js ./
# Instala dependências JS
RUN npm install

# Copia restante da aplicação (para que Vite funcione com Laravel)
COPY resources ./resources
COPY public ./public

# Executa o build do Vite
RUN npm run build

# Etapa 2: PHP + Composer
FROM php:8.2-fpm

# Instala dependências de sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libsqlite3-dev \
    libpq-dev \
    default-mysql-client \
    bash \
    gnupg \
    ca-certificates \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala extensões PHP
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Instala Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Define diretório de trabalho
WORKDIR /var/www

# Copia o restante da aplicação Laravel
COPY . .

# Copia o build do Vite da imagem Node
COPY --from=nodebuilder /app/public/build ./public/build

# Permissões (ajuste conforme sua hospedagem)
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

# Expondo porta do PHP-FPM
EXPOSE 9000

# Comando de inicialização
CMD ["php-fpm"]
