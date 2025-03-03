## PAREA BOT
*1.0.0*
-- Idealização do Projeto

*1.0.1*
-- Implementado Bot.js utilizando Baileys para enviar mensagens para o whatsapp em Localhost.



*1.0.2*
-- _PHP_ +  _PDOSQL/MYSQL 5.7 Implementado_ 
--Criação de Front End para adição de dados no DB
--Criação de Tabelas
    *users (guarda usuários principais)
    *users_operators (guarda os operadores por usuário)
    *operator_type (guarda os tipos de operadores)
    *user_stocks (guarda os estoques de cada usuário)
    *users_mov_estoques (movimentação de estoques para usuários)
    *products (produtos únicos)
    *estock_entradas Estoque que recebeu a entrada 
    *estock_saidas Estoque que realizou a saída
    
--Atualização de estrutura do Projeto 
    Parea/
    ├── node_modules/git 
    ├── .wwebjs_cache/
    ├── .wwebjs_auth/
    ├── db/
    │   ├── dbConnection.php       # Conexão ao banco de dados (PHP para o front)
    │   ├── dbConfig.js            # Configuração do banco para o bot
    │   ├── userQueries.js         # Queries de usuários (bot)
    │   ├── dbQueries.php          # Queries gerais para o front
    ├── bot.js                     # Código principal do bot
    ├── package.json
    ├── package-lock.json
    ├── public/                    # Arquivos acessíveis do front
    │   ├── css/                   # Estilos CSS
    │   │   ├── style.css          # Estilo global do front
    │   ├── js/                    # Scripts front-end
    │   │   ├── script.js          # Funções JS do front
    │   ├── img/
    │   │   ├── parea.png          # Imagem para o bot
    │   ├── index.html             # Página inicial do front
