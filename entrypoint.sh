#!/bin/bash

set -e

# Instala dependências PHP se necessário
if [ ! -d "vendor" ]; then
  echo "Instalando dependências do Composer..."
  composer install --no-dev --optimize-autoloader
fi

# Instala dependências Node.js se necessário
if [ ! -d "node_modules" ]; then
  echo "Instalando dependências NPM..."
  npm install
fi

# Garante permissão para executar Vite e builda os assets
echo "Buildando assets com Vite..."
chmod +x node_modules/.bin/vite || true
npm run build

# Limpa caches do Laravel (opcional)
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

exec "$@"
