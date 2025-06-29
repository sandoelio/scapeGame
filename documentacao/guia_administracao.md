# Guia de Administração - Escape Room Game

Este guia fornece instruções detalhadas para administradores do Escape Room Game, incluindo como gerenciar usuários, fases, desafios e outros elementos do jogo.

## Sumário

1. [Acesso ao Painel Administrativo](#acesso-ao-painel-administrativo)
2. [Gerenciamento de Usuários](#gerenciamento-de-usuários)
3. [Gerenciamento de Fases](#gerenciamento-de-fases)
4. [Gerenciamento de Desafios](#gerenciamento-de-desafios)
5. [Gerenciamento de NPCs](#gerenciamento-de-npcs)
6. [Gerenciamento de Itens](#gerenciamento-de-itens)
7. [Gerenciamento de Armadilhas](#gerenciamento-de-armadilhas)
8. [Monitoramento de Atividades](#monitoramento-de-atividades)
9. [Backup e Restauração](#backup-e-restauração)
10. [Configurações do Sistema](#configurações-do-sistema)

## Acesso ao Painel Administrativo

### Como Acessar

1. Faça login com uma conta de administrador
2. Acesse `/admin/dashboard` na URL do jogo
3. Você verá o painel administrativo com todas as opções disponíveis

### Criação de Conta de Administrador

Para criar uma conta de administrador:

1. Crie uma conta de usuário normal através da página de registro
2. Acesse o banco de dados diretamente ou use o Tinker:

```bash
php artisan tinker
```

3. Execute o seguinte comando para promover um usuário a administrador:

```php
$user = \App\Models\User::where('email', 'admin@example.com')->first();
$user->is_admin = true;
$user->save();
```

## Gerenciamento de Usuários

### Visualizar Usuários

1. No painel administrativo, clique em "Usuários"
2. Você verá uma lista de todos os usuários registrados
3. Use a barra de pesquisa para encontrar usuários específicos

### Editar Usuários

1. Na lista de usuários, clique no botão "Editar" ao lado do usuário desejado
2. Você pode modificar:
   - Nome
   - Email
   - Status de administrador
   - Status da conta (ativo/inativo)
3. Clique em "Salvar" para aplicar as alterações

### Excluir Usuários

1. Na lista de usuários, clique no botão "Excluir" ao lado do usuário desejado
2. Confirme a exclusão
3. Todos os dados associados ao usuário (personagens, progresso, etc.) serão excluídos

## Gerenciamento de Fases

### Visualizar Fases

1. No painel administrativo, clique em "Fases"
2. Você verá uma lista de todas as fases do jogo
3. As fases são exibidas na ordem em que aparecem no jogo

### Criar Nova Fase

1. Na página de fases, clique no botão "Nova Fase"
2. Preencha o formulário:
   - Nome: Nome da fase
   - Descrição: Descrição detalhada da fase
   - Ordem: Número que determina a ordem da fase no jogo
   - Dica (opcional): Dica que pode ser exibida aos jogadores
3. Clique em "Criar Fase" para salvar

### Editar Fase

1. Na lista de fases, clique no botão "Editar" ao lado da fase desejada
2. Modifique os campos conforme necessário
3. Clique em "Salvar" para aplicar as alterações

### Excluir Fase

1. Na lista de fases, clique no botão "Excluir" ao lado da fase desejada
2. Confirme a exclusão
3. Todos os desafios, NPCs, itens e armadilhas associados à fase serão excluídos

## Gerenciamento de Desafios

### Visualizar Desafios

1. No painel administrativo, clique em "Desafios"
2. Você verá uma lista de todos os desafios do jogo
3. Use o filtro por fase para ver apenas os desafios de uma fase específica

### Criar Novo Desafio

1. Na página de desafios, clique no botão "Novo Desafio"
2. Preencha o formulário:
   - Nome: Nome do desafio
   - Fase: Fase à qual o desafio pertence
   - Descrição: Descrição detalhada do desafio
   - Tipo: Tipo do desafio (puzzle, code, sequence)
   - Dificuldade: Nível de dificuldade (fácil, médio, difícil)
   - Pontos: Pontos de experiência concedidos ao completar
   - Ordem: Número que determina a ordem do desafio na fase
   - Código de Solução: Código que resolve o desafio
   - Dica (opcional): Dica que pode ser exibida aos jogadores
3. Clique em "Criar Desafio" para salvar

### Editar Desafio

1. Na lista de desafios, clique no botão "Editar" ao lado do desafio desejado
2. Modifique os campos conforme necessário
3. Clique em "Salvar" para aplicar as alterações

### Excluir Desafio

1. Na lista de desafios, clique no botão "Excluir" ao lado do desafio desejado
2. Confirme a exclusão
3. Todo o progresso dos jogadores relacionado a este desafio será excluído

## Gerenciamento de NPCs

### Visualizar NPCs

1. No painel administrativo, clique em "NPCs"
2. Você verá uma lista de todos os NPCs do jogo
3. Use o filtro por fase para ver apenas os NPCs de uma fase específica

### Criar Novo NPC

1. Na página de NPCs, clique no botão "Novo NPC"
2. Preencha o formulário:
   - Nome: Nome do NPC
   - Fase: Fase à qual o NPC pertence
   - Descrição: Descrição detalhada do NPC
   - Diálogo: Texto que o NPC dirá aos jogadores
3. Clique em "Criar NPC" para salvar

### Editar NPC

1. Na lista de NPCs, clique no botão "Editar" ao lado do NPC desejado
2. Modifique os campos conforme necessário
3. Clique em "Salvar" para aplicar as alterações

### Excluir NPC

1. Na lista de NPCs, clique no botão "Excluir" ao lado do NPC desejado
2. Confirme a exclusão

## Gerenciamento de Itens

### Visualizar Itens

1. No painel administrativo, clique em "Itens"
2. Você verá uma lista de todos os itens do jogo
3. Use o filtro por tipo para ver apenas os itens de um tipo específico

### Criar Novo Item

1. Na página de itens, clique no botão "Novo Item"
2. Preencha o formulário:
   - Nome: Nome do item
   - Fase: Fase onde o item pode ser encontrado
   - Descrição: Descrição detalhada do item
   - Tipo: Tipo do item (health_potion, key, tool, artifact)
   - Valor do Efeito: Valor numérico do efeito do item (ex: quantidade de cura)
   - Consumível: Indica se o item é consumível (sim/não)
3. Clique em "Criar Item" para salvar

### Editar Item

1. Na lista de itens, clique no botão "Editar" ao lado do item desejado
2. Modifique os campos conforme necessário
3. Clique em "Salvar" para aplicar as alterações

### Excluir Item

1. Na lista de itens, clique no botão "Excluir" ao lado do item desejado
2. Confirme a exclusão
3. O item será removido de todos os inventários de jogadores

## Gerenciamento de Armadilhas

### Visualizar Armadilhas

1. No painel administrativo, clique em "Armadilhas"
2. Você verá uma lista de todas as armadilhas do jogo
3. Use o filtro por fase para ver apenas as armadilhas de uma fase específica

### Criar Nova Armadilha

1. Na página de armadilhas, clique no botão "Nova Armadilha"
2. Preencha o formulário:
   - Nome: Nome da armadilha
   - Fase: Fase à qual a armadilha pertence
   - Descrição: Descrição detalhada da armadilha
   - Dano: Quantidade de dano causado pela armadilha
   - Condição de Ativação: Descrição de como a armadilha é ativada
   - Ativa: Indica se a armadilha está ativa (sim/não)
3. Clique em "Criar Armadilha" para salvar

### Editar Armadilha

1. Na lista de armadilhas, clique no botão "Editar" ao lado da armadilha desejada
2. Modifique os campos conforme necessário
3. Clique em "Salvar" para aplicar as alterações

### Excluir Armadilha

1. Na lista de armadilhas, clique no botão "Excluir" ao lado da armadilha desejada
2. Confirme a exclusão

## Monitoramento de Atividades

### Histórico de Ações

1. No painel administrativo, clique em "Histórico de Ações"
2. Você verá uma lista de todas as ações realizadas pelos jogadores
3. Use os filtros para refinar a busca:
   - Por usuário
   - Por personagem
   - Por tipo de ação
   - Por período

### Estatísticas do Jogo

1. No painel administrativo, clique em "Estatísticas"
2. Você verá gráficos e tabelas com informações sobre:
   - Número de usuários ativos
   - Desafios mais completados
   - Desafios mais difíceis (com base na taxa de conclusão)
   - Itens mais coletados
   - Armadilhas mais ativadas
   - Distribuição de níveis dos personagens

## Backup e Restauração

### Realizar Backup

1. No painel administrativo, clique em "Backup"
2. Selecione o que deseja incluir no backup:
   - Banco de dados
   - Arquivos de configuração
   - Arquivos de mídia
3. Clique em "Iniciar Backup"
4. O sistema gerará um arquivo ZIP que você pode baixar

### Restaurar Backup

1. No painel administrativo, clique em "Restaurar"
2. Faça upload do arquivo de backup
3. Selecione o que deseja restaurar:
   - Banco de dados
   - Arquivos de configuração
   - Arquivos de mídia
4. Clique em "Iniciar Restauração"
5. Aguarde a conclusão do processo

## Configurações do Sistema

### Configurações Gerais

1. No painel administrativo, clique em "Configurações"
2. Na aba "Geral", você pode ajustar:
   - Nome do jogo
   - Descrição do jogo
   - Email de contato
   - Logo do jogo
   - Favicon

### Configurações de Jogo

1. No painel administrativo, clique em "Configurações"
2. Na aba "Jogo", você pode ajustar:
   - Pontos de vida iniciais dos personagens
   - Taxa de ganho de experiência
   - Requisitos de experiência para subir de nível
   - Número máximo de itens no inventário
   - Tempo de revivificação após morte

### Configurações de Email

1. No painel administrativo, clique em "Configurações"
2. Na aba "Email", você pode ajustar:
   - Servidor SMTP
   - Porta SMTP
   - Usuário SMTP
   - Senha SMTP
   - Endereço de email do remetente
   - Nome do remetente

### Configurações de Segurança

1. No painel administrativo, clique em "Configurações"
2. Na aba "Segurança", você pode ajustar:
   - Tempo de expiração da sessão
   - Política de senhas
   - Limite de tentativas de login
   - Tempo de bloqueio após tentativas falhas
   - Habilitar/desabilitar registro de novos usuários

### Aplicar Configurações

Após fazer alterações nas configurações:

1. Revise todas as alterações
2. Clique em "Salvar Configurações"
3. O sistema pode precisar ser reiniciado para que algumas alterações entrem em vigor

## Suporte e Contato

Se você encontrar problemas ou tiver dúvidas sobre a administração do Escape Room Game, entre em contato com a equipe de desenvolvimento:

- Email: suporte@escaperoomgame.com
- Telefone: (00) 1234-5678
- Site: https://www.escaperoomgame.com/suporte

