<?php
// Verifica se os parâmetros obrigatórios foram passados
if (!isset($_GET['content'], $_GET['title'])) {
    http_response_code(400);
    echo 'Parâmetros insuficientes.';
    exit;
}

$contentUrl = $_GET['content'];
$title = htmlspecialchars($_GET['title'], ENT_QUOTES, 'UTF-8');

// Validação básica do caminho (evita path traversal e caminhos absolutos)
if (strpos($contentUrl, '..') !== false || str_starts_with($contentUrl, '/')) {
    http_response_code(400);
    echo 'Conteúdo inválido.';
    exit;
}

// Extrai parâmetros da URL (ex: foo.php?x=1)
$parts = explode('?', $contentUrl, 2);
$file = $parts[0];
parse_str($parts[1] ?? '', $params);

// Diretório base permitido
$allowedBaseDir = realpath(__DIR__ . '/../includes/painel'); // ajuste se quiser permitir mais pastas

// Caminho completo do arquivo
$requestedPath = realpath($allowedBaseDir . '/' . $file);

// Verifica se o arquivo existe e está dentro do diretório permitido
if ($requestedPath === false || !file_exists($requestedPath)) {
    http_response_code(404);
    echo 'Arquivo não encontrado.';
    exit;
}

if (strpos($requestedPath, $allowedBaseDir) !== 0) {
    http_response_code(403);
    echo 'Acesso proibido.';
    exit;
}

// Simula $_GET localmente para o arquivo incluído
$originalGet = $_GET;
$_GET = array_merge($_GET, $params);

// Inclui o conteúdo solicitado
include $requestedPath;

// Restaura $_GET original
$_GET = $originalGet;
?>
