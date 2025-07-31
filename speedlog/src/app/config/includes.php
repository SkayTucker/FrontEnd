<?php
// Inicia a sessão se ainda não foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('America/Sao_Paulo');
// Constantes de níveis de acesso
if (!defined('OPERADOR'))
    define('OPERADOR', 1);
if (!defined('ADMIN'))
    define('ADMIN', 0);

// Caminho absoluto até a raiz do projeto (Speedlog/)
// __DIR__ está em app/config, sobe 2 níveis até a raiz do projeto
define('ROOT_PATH', realpath(__DIR__ . '/../../')); // ex: C:/xampp/htdocs/Speedlog

// Definindo URLs base — necessário para uso em front-end
// Considerando que seu projeto está rodando em http://localhost/Speedlog/
// Ajuste o BASE_URL conforme o caminho virtual no servidor
// NÃO use ROOT_PATH aqui (que é um caminho físico no disco)
define('BASE_URL', '/Speedlog');

// Diretórios físicos absolutos
define('APP_PATH', ROOT_PATH . '/app');       // /.../Speedlog/app
define('SRC_PATH', ROOT_PATH . '/src');       // /.../Speedlog/src
define('PUBLIC_PATH', ROOT_PATH . '/public'); // /.../Speedlog/public

// Diretórios específicos
define('MODALS_PATH', SRC_PATH . '/modals'); // /.../Speedlog/src/modals
define('PAGES_PATH', SRC_PATH . '/pages');   // /.../Speedlog/src/pages
define('INCLUDES_PATH', APP_PATH . '/config'); // /.../Speedlog/app/config

// URLs públicas para frontend (para usar em tags <img>, <script>, etc)
// Como a pasta public é a raiz da parte pública, a url para assets será:
define('ASSETS_URL', BASE_URL . '/public/assets');
define('IMAGES_URL', ASSETS_URL . '/img');
define('MODALS_URL', BASE_URL . '/src/modals');
define('PAGES_URL', BASE_URL . '/src/pages');


// Banco de dados
require_once INCLUDES_PATH . '/private/db.php';

// Função para verificar autenticação
function verificarAutenticacao(bool $exigirLogin = true)
{
    if ($exigirLogin && empty($_SESSION['user_id'])) {
        // Redireciona para a página inicial do projeto (public/index.php ou raiz)
        header("Location: " . BASE_URL . "/index.php");
        exit();
    }
}
