# Guia de Instalação - Escape Room Game

Este guia fornece instruções detalhadas para instalar e configurar o Escape Room Game em diferentes ambientes.

## Sumário

1. [Requisitos do Sistema](#requisitos-do-sistema)
2. [Instalação em Ambiente de Desenvolvimento](#instalação-em-ambiente-de-desenvolvimento)
3. [Instalação em Ambiente de Produção](#instalação-em-ambiente-de-produção)
4. [Configuração do Banco de Dados](#configuração-do-banco-de-dados)
5. [Configuração do Servidor Web](#configuração-do-servidor-web)
6. [Solução de Problemas Comuns](#solução-de-problemas-comuns)

## Requisitos do Sistema

### Software Necessário

- PHP 8.1 ou superior
- Composer 2.0 ou superior
- Node.js 14.x ou superior
- npm 6.x ou superior
- Git

### Extensões PHP Necessárias

- BCMath
- Ctype
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- SQLite3
- Tokenizer
- XML
- Zip

### Requisitos de Hardware Recomendados

- CPU: 2 cores ou mais
- RAM: 2GB ou mais
- Espaço em disco: 1GB ou mais

## Instalação em Ambiente de Desenvolvimento

### 1. Clone o Repositório

```bash
git clone https://github.com/username/escape-room-game.git
cd escape-room-game
```

### 2. Instale as Dependências PHP

```bash
composer install
```

### 3. Instale as Dependências JavaScript

```bash
npm install
```

### 4. Configure o Ambiente

Copie o arquivo de exemplo de configuração e ajuste conforme necessário:

```bash
cp .env.example .env
```

Edite o arquivo `.env` para configurar o banco de dados:

```
DB_CONNECTION=sqlite
DB_DATABASE=/caminho/absoluto/para/escape-room-game/database/database.sqlite
```

### 5. Gere a Chave da Aplicação

```bash
php artisan key:generate
```

### 6. Crie o Arquivo do Banco de Dados SQLite

```bash
touch database/database.sqlite
```

### 7. Execute as Migrações e Seeders

```bash
php artisan migrate --seed
```

### 8. Compile os Assets

```bash
npm run dev
```

### 9. Inicie o Servidor de Desenvolvimento

```bash
php artisan serve
```

Agora você pode acessar o jogo em `http://localhost:8000`.

## Instalação em Ambiente de Produção

### 1. Clone o Repositório

```bash
git clone https://github.com/username/escape-room-game.git
cd escape-room-game
```

### 2. Instale as Dependências PHP para Produção

```bash
composer install --optimize-autoloader --no-dev
```

### 3. Instale as Dependências JavaScript e Compile para Produção

```bash
npm install
npm run build
```

### 4. Configure o Ambiente

Copie o arquivo de exemplo de configuração e ajuste conforme necessário:

```bash
cp .env.example .env
```

Edite o arquivo `.env` para configurar o ambiente de produção:

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seu-dominio.com

DB_CONNECTION=sqlite
DB_DATABASE=/caminho/absoluto/para/escape-room-game/database/database.sqlite
```

### 5. Gere a Chave da Aplicação

```bash
php artisan key:generate
```

### 6. Crie o Arquivo do Banco de Dados SQLite

```bash
touch database/database.sqlite
chmod 664 database/database.sqlite
```

### 7. Execute as Migrações e Seeders

```bash
php artisan migrate --seed
```

### 8. Configure as Permissões

```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 9. Configure o Cache para Produção

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Configuração do Banco de Dados

### SQLite (Padrão)

O Escape Room Game utiliza SQLite por padrão, que é um banco de dados leve e fácil de configurar.

1. Certifique-se de que a extensão SQLite3 está habilitada no PHP:

```bash
php -m | grep sqlite
```

2. Crie o arquivo do banco de dados:

```bash
touch database/database.sqlite
chmod 664 database/database.sqlite
```

3. Configure o arquivo `.env`:

```
DB_CONNECTION=sqlite
DB_DATABASE=/caminho/absoluto/para/escape-room-game/database/database.sqlite
```

### MySQL (Opcional)

Se preferir usar MySQL:

1. Crie um banco de dados e um usuário no MySQL:

```sql
CREATE DATABASE escape_room_game;
CREATE USER 'escape_user'@'localhost' IDENTIFIED BY 'sua_senha';
GRANT ALL PRIVILEGES ON escape_room_game.* TO 'escape_user'@'localhost';
FLUSH PRIVILEGES;
```

2. Configure o arquivo `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=escape_room_game
DB_USERNAME=escape_user
DB_PASSWORD=sua_senha
```

3. Execute as migrações:

```bash
php artisan migrate --seed
```

## Configuração do Servidor Web

### Apache

1. Certifique-se de que o mod_rewrite está habilitado:

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

2. Configure um Virtual Host:

```apache
<VirtualHost *:80>
    ServerName seu-dominio.com
    ServerAdmin webmaster@seu-dominio.com
    DocumentRoot /var/www/escape-room-game/public
    
    <Directory /var/www/escape-room-game/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/escape-room-error.log
    CustomLog ${APACHE_LOG_DIR}/escape-room-access.log combined
</VirtualHost>
```

3. Ative o Virtual Host e reinicie o Apache:

```bash
sudo a2ensite escape-room.conf
sudo systemctl restart apache2
```

### Nginx

1. Configure um bloco de servidor:

```nginx
server {
    listen 80;
    server_name seu-dominio.com;
    root /var/www/escape-room-game/public;
    
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";
    
    index index.php;
    
    charset utf-8;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
    
    error_page 404 /index.php;
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

2. Ative o bloco de servidor e reinicie o Nginx:

```bash
sudo ln -s /etc/nginx/sites-available/escape-room.conf /etc/nginx/sites-enabled/
sudo systemctl restart nginx
```

## Solução de Problemas Comuns

### Erro: "No application encryption key has been specified"

Execute o comando:

```bash
php artisan key:generate
```

### Erro: "The stream or file "/var/www/escape-room-game/storage/logs/laravel.log" could not be opened"

Ajuste as permissões:

```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Erro: "Class 'SQLite3' not found"

Instale a extensão SQLite3 para PHP:

```bash
sudo apt-get install php8.1-sqlite3
sudo systemctl restart apache2  # ou nginx
```

### Erro: "Failed to open stream: Permission denied"

Verifique se o arquivo do banco de dados SQLite existe e tem as permissões corretas:

```bash
touch database/database.sqlite
chmod 664 database/database.sqlite
chown www-data:www-data database/database.sqlite
```

### Erro: "The page has expired due to inactivity"

Isso geralmente ocorre devido a um erro de CSRF. Certifique-se de que o formulário inclui o token CSRF:

```html
@csrf
```

### Erro: "404 Not Found" ao acessar rotas

Verifique se o mod_rewrite está habilitado (Apache) ou se a configuração do Nginx está correta para processar o arquivo .htaccess.

### Erro: "Connection refused" ao acessar o site

Verifique se o servidor web está em execução:

```bash
sudo systemctl status apache2  # ou nginx
```

### Erro: "Allowed memory size exhausted"

Aumente o limite de memória no php.ini:

```
memory_limit = 256M
```

### Erro: "Maximum execution time exceeded"

Aumente o tempo máximo de execução no php.ini:

```
max_execution_time = 120
```

Para mais informações ou suporte, entre em contato com a equipe de desenvolvimento.

