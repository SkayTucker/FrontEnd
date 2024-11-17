import { makeWASocket, useMultiFileAuthState, DisconnectReason, Browsers } from '@whiskeysockets/baileys';
import * as fs from 'fs';
import { Boom } from '@hapi/boom';

// Caminho onde o estado de autenticação será armazenado
const authPath = './auth_info_baileys';

// Função para salvar e restaurar o estado de autenticação
const { state, saveCreds } = await useMultiFileAuthState(authPath);

// Objeto para rastrear as últimas mensagens processadas
const recentMessages = new Map();

async function connectToWhatsApp() {
    // Cria a conexão com o WhatsApp
    const sock = makeWASocket({
        auth: state, // Usa o estado de autenticação para evitar escanear o QR novamente
        browser: Browsers.macOS('Desktop'),
        printQRInTerminal: true, // Exibe o QR no terminal
        syncFullHistory: true // Sincroniza todo o histórico de mensagens
    });

    // Lida com eventos de conexão
    sock.ev.on('connection.update', (update) => {
        const { connection, lastDisconnect, qr } = update;
        if (connection === 'close') {
            const shouldReconnect = (lastDisconnect.error && lastDisconnect.error.output && lastDisconnect.error.output.statusCode !== DisconnectReason.loggedOut);
            console.log('Conexão fechada devido a', lastDisconnect.error, ', reconectando', shouldReconnect);
            if (shouldReconnect) {
                connectToWhatsApp(); // Tenta reconectar automaticamente
            }
        } else if (connection === 'open') {
            console.log('Conexão aberta com sucesso!');
        } else if (qr) {
            console.log('QR code gerado. Escaneie com o WhatsApp:');
            console.log(qr); // Exibe diretamente o QR code gerado
        }
    });

    // Lida com mensagens recebidas
    sock.ev.on('messages.upsert', async (m) => {
        const message = m.messages[0];
        const sender = message.key.remoteJid; // ID do remetente da mensagem
        const messageId = message.key.id;    // ID único da mensagem recebida

        console.log('Mensagem recebida:', JSON.stringify(m, undefined, 2));

        // Evita responder para si mesmo
        if (message.key.fromMe) {
            console.log('Mensagem recebida de si mesmo, ignorando...');
            return;
        }

        // Verifica se a mensagem já foi processada
        if (recentMessages.get(sender) === messageId) {
            console.log('Mensagem já processada, ignorando...');
            return;
        }

        // Armazena o ID da mensagem processada para evitar duplicação
        recentMessages.set(sender, messageId);

        // Define um tempo para limpar a memória (opcional, evita acumular dados antigos)
        setTimeout(() => recentMessages.delete(sender), 60 * 1000); // Remove após 1 minuto

        // Responde automaticamente à mensagem recebida
        if (sender) {
            await sock.sendMessage(sender, { text: '*PARÊA BOT*: \nOlá, estou aqui!' });
            console.log(`Resposta enviada para ${sender}`);
        }
    });

    // Salva as credenciais sempre que o estado de autenticação for alterado
    sock.ev.on('creds.update', saveCreds);

    return sock;
}

// Função principal para inicializar a conexão
async function main() {
    await connectToWhatsApp();
}

main().catch(err => console.error('Erro ao conectar:', err));
