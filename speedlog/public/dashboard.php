<?php
session_start();

require_once '../src/app/config/includes.php';
require_once '../src/app/config/querys.php';

verificarAutenticacao(); // Protege acesso sem login

include '../src/components/header.php';
?>

<main>
    <?php include '../src/components/menuSidebar.php'; ?>

    <div class="container">

        <div class="dashboardContent fadeIn">
            <?php
            // Define as páginas permitidas por nível de acesso
            $nivel = $_SESSION['nivel_acesso'] ?? null;

            $pagesPorNivel = [
                '0' => ['main-admin', 'newUser', 'veiculos'],       // Admin
                '1' => ['main-operador', 'veiculos'],               // Operador
            ];

            if (!isset($pagesPorNivel[$nivel])) {
                echo "<p>" . htmlspecialchars("Nível de acesso não reconhecido.", ENT_QUOTES, 'UTF-8') . "</p>";
                exit;
            }

            // Limpa e valida parâmetro `page`
            $page = $_GET['page'] ?? '';
            $page = preg_replace('/[^a-z0-9\-]/i', '', $page);

            $allowedPages = $pagesPorNivel[$nivel];

            // Define página padrão se nenhuma foi passada
            if (empty($page)) {
                $page = $allowedPages[0];
            }

            // Verifica se a página é permitida e existe fisicamente
            $pagePath = "../src/pages/{$page}.php";

            if (in_array($page, $allowedPages, true) && file_exists($pagePath)) {
                include $pagePath;
            } else {
                echo "<p>" . htmlspecialchars("Página não encontrada ou acesso negado.", ENT_QUOTES, 'UTF-8') . "</p>";
            }
            ?>
        </div>
    </div>
</main>

<?php include '../src/components/footer.php'; ?>