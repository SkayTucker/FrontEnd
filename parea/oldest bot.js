const { Client, LocalAuth } = require('whatsapp-web.js');
const qrcode = require('qrcode-terminal');
const { isUserRegistered, validateUser } = require('./db/userQueries');

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

// Função para enviar o menu inicial
function sendSimulatedButtons(chatId) {
    const message = `*Parea Bot* 🤖:

Escolha uma opção:

1️⃣ Entrar/Login 🛠️
2️⃣ Veja nossa imagem 📷
3️⃣ Informações sobre a empresa ℹ️
4️⃣ Nome da CEO e empresa desenvolvedora 👩‍💻
5️⃣ Encerrar conversa ❌`;

    client.sendMessage(chatId, message);
}

client.on('message', async (message) => {
    console.log('Mensagem recebida:', message.body);

    if (message.body.toLowerCase() === 'olá') {
        sendSimulatedButtons(message.from); // Envia o menu inicial
        return;
    }

    if (!message.hasQuotedMsg && !message.body.match(/^\d+$/)) {
        client.sendMessage(message.from, 'Por favor, inicie a conversa enviando *Olá*');
        return;
    }

    try {
        let awaitingKeyword = false;

        switch (message.body) {
            case '1': // Login
                const isRegistered = await isUserRegistered(message.from);

                if (isRegistered) {
                    await client.sendMessage(message.from, 'Número de telefone validado. Qual é a sua palavra-chave?');
                    awaitingKeyword = true;
                } else {
                    await client.sendMessage(message.from, 'Número não registrado. Por favor, entre em contato com o suporte.');
                }
                break;
            case '2': // Imagem
                await client.sendMessage(message.from, 'Você escolheu "Veja nossa imagem" 📷.');
                await client.sendMessage(message.from, {
                    media: './parea.png', // Atualize o caminho da imagem
                    caption: 'Aqui está a imagem da Parea Tech! 📸'
                });
                break;
            case '3': // Razão social
                await client.sendMessage(message.from, 'Razão social: Gestão de Estoque com Bot API WhatsApp ℹ️.');
                break;
            case '4': // CEO e empresa desenvolvedora
                await client.sendMessage(
                    message.from,
                    'CEO: Amanda Freitas 👩‍💻\nEmpresa desenvolvedora: DragonDev 🐉.'
                );
                break;
            case '5': // Encerrar conversa
                await client.sendMessage(message.from, 'Conversa encerrada. Envie *Olá* para iniciar novamente. ❌');
                return; // Não exibe o menu novamente
            default:
                // Processa a palavra-chave, se estiver aguardando
                if (awaitingKeyword) {
                    const isValid = await validateUser(message.from, message.body);

                    if (isValid) {
                        await client.sendMessage(message.from, 'Login realizado com sucesso! Bem-vindo ao sistema de gerenciamento de estoque.');
                    } else {
                        await client.sendMessage(message.from, 'Palavra-chave incorreta. Tente novamente.');
                    }
                    awaitingKeyword = false;
                } else {
                    await client.sendMessage(message.from, 'Opção inválida. Por favor, escolha uma das opções do menu.');
                }
                break;
        }

        // Após processar a resposta, exibe o menu inicial novamente
        sendSimulatedButtons(message.from);
    } catch (error) {
        console.error('Erro no sistema:', error);
        client.sendMessage(message.from, 'Ocorreu um erro no sistema. Por favor, tente novamente mais tarde.');
    }
});

client.initialize();
