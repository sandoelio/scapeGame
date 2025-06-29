# Escape Room Game - Documentação

Este diretório contém a documentação completa do jogo Escape Room, desenvolvido em Laravel/PHP.

## Arquivos de Documentação

1. [Manual do Usuário](manual_usuario.md) - Guia completo para jogadores, explicando como jogar o jogo.
2. [Documentação Técnica](documentacao_tecnica.md) - Documentação detalhada da arquitetura e implementação do sistema.
3. [Guia de Instalação](guia_instalacao.md) - Instruções passo a passo para instalar e configurar o jogo.
4. [Guia de Administração](guia_administracao.md) - Instruções para administradores do jogo.
5. [Relatório de Testes](../testes_realizados.md) - Relatório dos testes realizados no jogo.

## Visão Geral do Projeto

O Escape Room Game é um jogo web baseado no conceito de salas de escape, onde os jogadores precisam resolver desafios e enigmas para avançar entre fases. O jogo foi desenvolvido utilizando Laravel/PHP e banco de dados SQLite, com um frontend responsivo utilizando Bootstrap.

### Principais Funcionalidades

- Sistema de autenticação de usuários
- Criação e gerenciamento de personagens
- Sistema de fases com desafios progressivos
- Interação com NPCs (personagens não jogáveis)
- Sistema de inventário para coleta e uso de itens
- Armadilhas que causam dano aos personagens
- Sistema de progresso e experiência

### Tecnologias Utilizadas

- Laravel 10.x
- PHP 8.1
- SQLite
- Bootstrap 5
- JavaScript/jQuery
- HTML5/CSS3

## Contato

Para mais informações ou suporte, entre em contato com a equipe de desenvolvimento.

## Rodando com Docker

Para rodar o sistema utilizando Docker e Docker Compose, siga os passos abaixo:

### Pré-requisitos

- Docker
- Docker Compose

### Configuração

1.  **Navegue até o diretório raiz do projeto** no seu terminal.
2.  **Crie o arquivo `.env`** a partir do `.env.example`:
    ```bash
    cp .env.example .env
    ```
3.  **Atualize as configurações do banco de dados** no arquivo `.env` para usar MySQL:
    ```
    DB_CONNECTION=mysql
    DB_HOST=mysql
    DB_PORT=3306
    DB_DATABASE=escape_room_game
    DB_USERNAME=root
    DB_PASSWORD=root_password
    ```
    (Você pode alterar `DB_DATABASE`, `DB_USERNAME` e `DB_PASSWORD` conforme sua preferência no `.env` e no `docker-compose.yml`)

### Subindo o Ambiente

### De permissão de execução ao script de entrada:

```bash
    chmod +x docker/entrypoint.sh
```

1.  **Construa as imagens Docker** (isso pode levar alguns minutos na primeira vez):
    ```bash
    docker compose build
    ```
2.  **Inicie os contêineres Docker**:
    ```bash
    docker compose up -d
    ```
3.  **Execute as migrações e seeders do Laravel** dentro do contêiner da aplicação (isso criará as tabelas e populará o banco de dados MySQL):
    ```bash
    docker exec -it escape-room-game-app-1 bash
    php artisan migrate:fresh --seed

    ```

O sistema estará acessível em `http://localhost` no seu navegador.

### Parando o Ambiente

Para parar os contêineres Docker:

```bash
docker compose down
```
### dentro do container rode
```bash
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache



