//OLD PELO USO DO IF-ELSE, TROCADO PELO PAREA 1.3 SWITCH-CASE



const { Client, LocalAuth, MessageMedia } = require('whatsapp-web.js');
const qrcode = require('qrcode-terminal');
const path = require('path');  // Para lidar com o caminho da imagem

// Criação do cliente, usando o LocalAuth para armazenar dados de login
const client = new Client({
    authStrategy: new LocalAuth(),
    puppeteer: { headless: true }
});

// Evento disparado quando o QR code for gerado
client.on('qr', (qr) => {
    console.log('QR Code recebido:');
    qrcode.generate(qr, { small: true });  // Gerar QR code no console
});

// Evento disparado quando o cliente está pronto e conectado
client.on('ready', () => {
    console.log('Cliente está pronto!');
});

// Função para enviar a mensagem com "botões" simulados
function sendSimulatedButtons(chatId) {
    const message = `*Parea Bot* 🤖:

Escolha uma opção:

1️⃣ Acesse o site 🌐
2️⃣ Enviar imagem do Parea Tech 🖼️
3️⃣ Ver informações sobre a razão social 📜
4️⃣ Nome da CEO e empresa desenvolvedora 👩‍💼
5️⃣ Encerrar conversa ❌`;

    client.sendMessage(chatId, message);  // Envia a mensagem com as opções
}

// Evento para monitorar as mensagens recebidas
client.on('message', async message => {
    console.log('Mensagem recebida:', message.body);

    if (message.body.toLowerCase() === 'olá') {
        sendSimulatedButtons(message.from);  // Envia a mensagem com as opções quando "Olá" for recebido
    } else if (message.body === '1') {
        client.sendMessage(message.from, 'Você escolheu "Acesse o site" 🌐. Acesse em: https://web.whatsapp.com');
    } else if (message.body === '2') {
        const imagePath = path.resolve(__dirname, 'parea_tech.jpg'); // Caminho para a imagem
        const media = MessageMedia.fromFilePath(imagePath);
        client.sendMessage(message.from, media, { caption: 'Aqui está a imagem do Parea Tech 🖼️.' });
    } else if (message.body === '3') {
        client.sendMessage(message.from, 'A razão social da empresa PareaTech é Gestão de Estoque com Bot API WhatsApp 📜.');
    } else if (message.body === '4') {
        client.sendMessage(message.from, 'A CEO da empresa é Amanda Freitas 👩‍💼, e a empresa desenvolvedora é DragonDev 🐉.');
    } else if (message.body === '5') {
        client.sendMessage(message.from, 'Conversa encerrada. Aguardo você iniciar novamente com um *Olá* ❌.');
    } else {
        client.sendMessage(message.from, 'Por favor, inicie a conversa com um *Olá* para continuar.');
    }
});

// Iniciar o cliente
client.initialize();