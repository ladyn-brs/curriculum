#!/bin/bash

# Variaveis
USUARIO=u503068802
SERVIDOR=212.85.6.191
DESTINO=/home/u503068802/domains/pkcia.com.br/public_html

# Comando de deploy
rsync -avz --delete \
--include="public/build/***" \
--exclude=".git" \
--exclude="node_modules" \
--exclude="storage/logs/*" \
--exclude="vendor" \
./ $USUARIO@212.85.6.191

# Comando remoto para gerar os vendors e limpar caches
ssh u503068802@212.85.6.191 << 'ENDSSH'
cd /home/212.85.6.191/domains/pkcia.com.br/public_html
composer install --no-dev --optimize-autoloader
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
ENDSSH

echo "? Deploy finalizado com sucesso!"
