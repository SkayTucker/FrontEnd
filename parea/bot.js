const { Client, LocalAuth, MessageMedia, Buttons } = require('whatsapp-web.js');
const qrcode = require('qrcode-terminal');
const { isUserRegistered, validateUser, getEstablishmentNameByPhone } = require('./db/userQueries');
const path = require('path');
const userStates = {};  // Objeto para armazenar o estado de login de cada usuário


// Função para verificar se o usuário está logado
function isUserLoggedIn(phoneNumber) {
    return userStates[phoneNumber] && userStates[phoneNumber].isLoggedIn;
}


const client = new Client({
    authStrategy: new LocalAuth(),
    puppeteer: { headless: true }
});

client.on('qr', (qr) => {
    console.log('QR Code recebido:');
    qrcode.generate(qr, { small: true });
});

client.on('ready', () => {
    console.log('Cliente está pronto!');
});


// Função para enviar botões
function sendButtonMessage(chatId) {
    const buttons = [
        { id: 'customId', body: 'Entrar/Login 🛠️' },
        { body: 'Veja nossa imagem 📷' },
        { body: 'Informações sobre a empresa ℹ️' },
        { body: 'Nome da CEO e empresa desenvolvedora 👩‍💻' },
        { body: 'Encerrar conversa ❌' },
    ];

    // Cria o objeto Buttons com o texto e os botões formatados
    const formattedButtons = new Buttons(
        'Escolha uma das opções abaixo:', 
        buttons, 
        'Menu Inicial', 
        'Parea Bot 🤖'
    );

    // Envia a mensagem com os botões
    client.sendMessage(chatId, formattedButtons);
}


// Função para o menu após login (logado)
function sendLoggedButtons(chatId) {
    const message = `*Parea Bot* 🤖:

Escolha uma opção:

1️⃣ Cadastrar Produto 🛒
2️⃣ Ver Produtos 📦
3️⃣ Ver Validades 📅
4️⃣ Voltar para Home ⬅️`;

    client.sendMessage(chatId, message);
}


client.on('message', async (message) => {
    const phoneNumber = message.from;  // Número de telefone do remetente
    const messageBody = message.body.trim();  // Mensagem enviada pelo usuário
    
    if (messageBody.toLowerCase() === 'olá') {
        // Responde com Menu
        sendButtonMessage(message.from);  // Exibe o menu para o usuário
        return;
    }

    try {
        if (!isUserLoggedIn(phoneNumber)) {
            if (messageBody === 'entrar/login') {
                // Realizar a validação de login...
                // Lógica de login
            } else if (messageBody === 'veja nossa imagem') {
                // Enviar imagem...
            } else if (messageBody === 'informações sobre a empresa') {
                // Informações da empresa...
            } else if (messageBody === 'nome da ceo e empresa desenvolvedora') {
                // Nome da CEO e desenvolvedora...
            } else if (messageBody === 'encerrar conversa') {
                client.sendMessage(phoneNumber, 'Conversa encerrada. Envie *Olá* para iniciar novamente.');
            } else {
                client.sendMessage(phoneNumber, 'Opção inválida. Envie *Olá* para ver as opções disponíveis.');
            }
        } else {
            // Se o usuário está logado, mostra o menu de opções pós-login
            switch (messageBody) {
                case '1': // Cadastrar Produto
                    client.sendMessage(phoneNumber, 'Por favor, informe os detalhes do produto.');
                    registerProduct(phoneNumber);
                    break;
                case '2': // Ver Produtos
                    client.sendMessage(phoneNumber, 'Buscando produtos...');
                    listProducts(phoneNumber);
                    break;
                case '3': // Histórico de Cadastro
                    client.sendMessage(phoneNumber, 'Por favor, informe a data de validade no formato (YYYY-MM-DD).');
                    client.once('message', (dateMessage) => {
                        const expiryDate = dateMessage.body.trim();
                        viewByExpiryDate(phoneNumber, expiryDate);
                    });
                    break;
                case '4': // Voltar para Home
                    sendSimulatedButtons(phoneNumber);  // Exibe o menu para o usuário
                    break;
                default:
                    client.sendMessage(phoneNumber, 'Opção inválida. Envie "1", "2", "3" ou "4".');
            }
        }
    } catch (error) {
        console.error('Erro no bot:', error);
        client.sendMessage(phoneNumber, 'Erro no sistema. Tente novamente mais tarde.');
    }
});

client.initialize();
