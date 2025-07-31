<?php
function sendToTelegramBot($photoPath, $vehiclePlate) {
    $botToken = 'SEU_BOT_TOKEN_AQUI';
    $chatId = 'SEU_CHAT_ID_AQUI';
    
    $telegramUrl = "https://api.telegram.org/bot{$botToken}/sendPhoto";
    
    // Mensagem com informações do veículo
    $caption = "🚗 Novo Upload de Veículo\n";
    $caption .= "Placa: " . strtoupper($vehiclePlate) . "\n";
    $caption .= "Data: " . date('d/m/Y H:i:s') . "\n";
    $caption .= "Upload via Sistema Speedlog";
    
    try {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $telegramUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $postFields = [
            'chat_id' => $chatId,
            'photo' => new CURLFile(realpath($photoPath)),
            'caption' => $caption
        ];
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if ($httpCode !== 200) {
            error_log("Erro ao enviar para Telegram: " . $response);
            return false;
        }
        
        $responseData = json_decode($response, true);
        return $responseData['ok'] ?? false;
        
    } catch (Exception $e) {
        error_log("Exception ao enviar para Telegram: " . $e->getMessage());
        return false;
    }
}

// Função para notificar sobre novas solicitações
function notifyAdminsAboutNewRequest($requestId) {
    // Implementação similar, buscando dados da solicitação e enviando para o Telegram
}