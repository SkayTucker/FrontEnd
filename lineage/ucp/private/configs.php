<?php require_once('../private/configs.php'); $siteVinculed = 1;

###########################################################
##                   Configurações                       ##
###########################################################
$panel_url = 'localhost/ucp'; // Digite exatamente o URL onde se encontra este painel (exemplo: www.l2server.com/ucp)
$themeColor = 'purple'; // Qual a tonalidade de cor predominante na template? (Escolha: default, black, blue, red, green ou purple)
$defaultLang = 'pt'; // Idioma padrão do painel (Escolha entre: PT, EN ou ES) - O painel conta com um sistema inteligente que detecta o idioma do navegador do usuário e exibe tudo naquele idioma, mas caso não consigamos detectar ou caso o navegador esteja num idioma diferente dos três citados anteriormente, o idioma setado aqui será o exibido
$gmt = '0'; // Se os scripts do painel estiverem num horário adiantado ou atrasado, altere o GMT. Exemplo: -1 (-1 hora), +3 (+3 horas), etc


###########################################################
##              Controle de funcionalidades              ##
###########################################################
// Quais funcionalidades estão disponíveis para os jogadores? (1 = Disponível | 0 = Indisponível)
$funct['regist'] = 0; // Se cadastrar através do painel
$funct['forgot'] = 0; // Recuperar conta através do painel
$funct['donate'] = 1; // Fazer doações/adquirir moedas
$funct['trnsf1'] = 1; // Transferir moedas online para um personagem in-game - Possibilita converter seu saldo para coins/ticket in-game
$funct['trnsf2'] = 1; // Transferir moedas online para outra conta
$funct['trnsf3'] = 0; // Transferir moedas de um personagem in-game para saldo online - Possibilita acrescentar saldo removendo coins/ticket in-game
$funct['gamst1'] = 0; // Game Stats - Top PvP
$funct['gamst2'] = 0; // Game Stats - Top PK
$funct['gamst3'] = 0; // Game Stats - Top Clan
$funct['gamst4'] = 0; // Game Stats - Top Online
$funct['gamst5'] = 0; // Game Stats - Grand Olympiad
$funct['gamst6'] = 0; // Game Stats - Boss Status
$funct['gamst7'] = 0; // Game Stats - Castle & Siege
$funct['gamst8'] = 0; // Game Stats - Top Level
$funct['gamst9'] = 0; // Game Stats - Top Adena
$funct['gams10'] = 0; // Game Stats - Boss Jewels Control
$funct['galle1'] = 0; // Galeria - Enviar Screenshot
$funct['galle2'] = 0; // Galeria - Enviar Vídeo
$funct['galle3'] = 0; // Galeria - Visualizar
$funct['config'] = 1; // Configurações (alterar dados da conta)


###########################################################
##                Cadastro e Recuperação                 ##
###########################################################
// Caso indisponibilize acima as opções de "Se cadastrar através do painel" ou "Recuperar conta através do painel", você pode inserir abaixo links externos para que os jogadores possam se cadastrar ou recuperar suas contas em uma página externa (caso deixe em branco, as opções irão sumir)
$link_regist = ""; // Link da página externa de cadastro
$link_forgot = ""; // Link da página externa de recuperar


###########################################################
##                  Aquisição de Saldo                   ##
###########################################################
$coinName = 'Ticket Donate'; // Nome da moeda online que representa o saldo (usada apenas no painel de usuário)
$coinName_mini = 'Coin'; // Nome resumido da moeda
$coinQntV = 1; // Qual a quantidade comercializada? Você definirá o valor dessa quantidade logo abaixo. (ex: se definir 10 aqui e nas configurações dos "Modulos de doação" abaixo definir 1.00 como valor, o usuário poderá adquirir 10 por R$ 1,00, 20 por R$ 2,00, etc)

// Bonus em porcentagem ao adquirir moeda online em altas quantidades (Exemplo: a cada 100 moedas compradas, ganha 10%, ou seja, paga pelas 100, mas recebe 110)
$bonusActived = 0; // Deseja habilitar a bonificação por compra em quantidade? (1 = Sim | 0 = Não)

// Você pode inserir até 3 bonificações! Caso não queira usar alguma, basta setar os valores como '0' que será desconsiderada.

// Bonificação 1:
$buyCoins['bonus_count'][1] = '100'; // A partir de qual quantidade o bônus abaixo é dado?
$buyCoins['bonus_percent'][1] = '20'; // Qual a porcentagem de bonificação?

// Bonificação 2:
$buyCoins['bonus_count'][2] = '400'; // A partir de qual quantidade o bônus abaixo é dado?
$buyCoins['bonus_percent'][2] = '50'; // Qual a porcentagem de bonificação?

// Bonificação 3:
$buyCoins['bonus_count'][3] = '800'; // A partir de qual quantidade o bônus abaixo é dado?
$buyCoins['bonus_percent'][3] = '100'; // Qual a porcentagem de bonificação?

// Exclusão de fatura
$delFatura = 1; // O usuário pode excluir uma fatura? (1 = Sim | 0 = Não) - OBS: Uma fatura nunca é excluída, ela é ocultada, mas sempre permanecerá no banco de dados.


###########################################################
##         Transferência por coin/ticket in-game         ##
###########################################################
// Caso esteja habilitada a funcionalidade "Transferir moedas online para um personagem in-game", o jogador poderá converter seu saldo online para moedas in-game! Precisamos definir algumas informações...
$coinGame = 'Ticket Donate'; // Nome da moeda donate in-game (geralmente Coin, Ticket ou Gold)
$coinID = 9512; // ID da moeda


###########################################################
##                   Modulos de doação                   ##
###########################################################

$autoDelivery = 1; // Você deseja que a entrega do saldo seja feita de forma automática? (1 = Sim | 0 = Não) (se optar de forma manual, as doações pagas ficarão com status "Paga". Você terá que ir até o painel admin e concluí-las clicando no botão "Entregar". Quando concluir, o saldo será adicionado e o status passará a ser "Entregue")
$donateEmail = 'https://www.facebook.com/l2buy'; // Email que receberá os comprovantes de pagamento para as transações bancárias e módulos de confirmação manual

// PAGSEGURO CONFIGS:
$PagSeguro['actived'] = 0; // Opção ativa? (1 = Sim / 0 = Não)
$PagSeguro['email'] = 'rilk_koyot@hotmail.com'; // Email da conta que receberá as doações
$PagSeguro['token'] = '___TOKEN_AQUI___'; // Token gerado no PagSeguro
$PagSeguro['token_sandbox'] = '___TOKEN_AQUI___'; // Token gerado no ambiente de testes do PagSeguro
$PagSeguro['coin_price'] = '1.00'; // Valor da quantidade comercializada (em Reais)

// PAYPAL CONFIGS:
$PayPal['actived'] = 1; // Opção ativa? (1 = Sim / 0 = Não)
$PayPal['business_email'] = 'karolveras77@gmail.com'; // Email da conta que receberá as doações
$PayPal['USD']['coin_price'] = '0.72'; // Valor da quantidade comercializada (em Dolar)
$PayPal['BRL']['coin_price'] = '3.00'; // Valor da quantidade comercializada (em Reais)
$PayPal['EUR']['coin_price'] = '0.62'; // Valor da quantidade comercializada (em Euros)

// MERCADOPAGO CONFIGS:
$MercadoPago['actived'] = 1; // Opção ativa? (1 = Sim / 0 = Não)
$MercadoPago['client_id'] = '3298630554294652'; // "CLIENT_ID" presente na página https://www.mercadopago.com/mlb/account/credentials?type=basic
$MercadoPago['client_secret'] = 'iogmb3rUvQ37YzqvDcrj0zxNRlwI9234'; // "CLIENT_SECRET" presente na página https://www.mercadopago.com/mlb/account/credentials?type=basic
$MercadoPago['currency'] = 'BRL'; // Código da moeda
$MercadoPago['symbol'] = 'R$'; // Símbolo da moeda
$MercadoPago['coin_price'] = '3.00'; // Valor da quantidade comercializada (em Reais)

// PAYGOL CONFIGS:
$PayGol['service_id'] = '___SERVICE_ID___'; // "Service ID" obtido na página https://secure.paygol.com/dashboard/notifications
$PayGol['secret_key'] = '___SECRET_KEY___'; // "Secret key" obtido na página https://secure.paygol.com/dashboard/notifications
$PayGol['USD']['actived'] = 0; // Dolar ativo? (1 = Sim / 0 = Não)
$PayGol['USD']['coin_price'] = '0.40'; // Valor da quantidade comercializada (em Dolar)
$PayGol['BRL']['actived'] = 0; // Real ativo? (1 = Sim / 0 = Não)
$PayGol['BRL']['coin_price'] = '1.00'; // Valor da quantidade comercializada (em Reais)
$PayGol['EUR']['actived'] = 0; // Euro ativo? (1 = Sim / 0 = Não)
$PayGol['EUR']['coin_price'] = '0.30'; // Valor da quantidade comercializada (em Euros)

// PAYOP CONFIGS:
$PayOp['actived'] = 0; // Opção ativa? (1 = Sim / 0 = Não)
$PayOp['public_key'] = 'application-___'; // PayOp Public Key
$PayOp['secret_key'] = '___SECRET_KEY___'; // PayOp Secret Key
$PayOp['JWTToken'] = '___JWT_Token___'; // JWT Token obtido em https://payop.com/en/profile/settings/jwt-token
$PayOp['currency'] = 'USD'; // Código da moeda
$PayOp['coin_price'] = '1.00'; // Valor da quantidade comercializada
$PayOp['autoDelivery'] = 1; // Você deseja que a entrega do saldo seja feita de forma automática? (1 = Sim | 0 = Não) (se optar de forma manual, as doações pagas ficarão com status "Paga". Você terá que ir até o painel admin e concluí-las clicando no botão "Entregar". Quando concluir, o saldo será adicionado e o status passará a ser "Entregue")

// PICPAY:
$PicPay['actived'] = 0; // Opção ativa? (1 = Sim / 0 = Não)
$PicPay['currency'] = 'BRL'; // Código da moeda
$PicPay['coin_price'] = '3.00'; // Valor da quantidade comercializada
$PicPay['name'] = '@jose.rilk';

// Conta Bancaria:
$Banking['actived'] = 0; // Opção ativa? (1 = Sim / 0 = Não)
$Banking['currency'] = 'BRL'; // Código da moeda
$Banking['coin_price'] = '3.00'; // Valor da quantidade comercializada
$Banking['bank_dados'] = 'CPF: 015.974.912-33';

// PIX NUBANK:
$Pix['actived'] = 0; // Opção ativa? (1 = Sim / 0 = Não)
$Pix['currency'] = 'BRL'; // Código da moeda
$Pix['coin_price'] = '3.00'; // Valor da quantidade comercializada
$Pix['pix_dados'] = '';

