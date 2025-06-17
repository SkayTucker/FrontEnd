<?php
// Caminho para o arquivo que será baixado
$arquivo = 'xciptv-player.apk'; // Exemplo: 'arquivos/meuarquivo.zip'

// Verifica se o arquivo existe
if (file_exists($arquivo)) {
    // Define os cabeçalhos para forçar o download
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($arquivo) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($arquivo));
    
    // Limpa o buffer de saída
    ob_clean();
    flush();
    
    // Lê o arquivo e envia para o navegador
    readfile($arquivo);
    exit;
} else {
    echo 'Arquivo não encontrado.';
}
?>
