<?php
$page = $_GET['page'] ?? '';
$nivel = $_SESSION['nivel_acesso'] ?? '';

// Define a página inicial conforme o nível de acesso
if ($nivel === 'ADMIN') {
    $paginaInicial = 'main-admin';
} elseif ($nivel === 'OPERADOR') {
    $paginaInicial = 'main-operador';
} else {
    $paginaInicial = 'main'; // fallback padrão
}
?>

<!-- Overlay escuro ao abrir sidebar no mobile -->
<div id="sidebar-overlay" class="sidebar-overlay"></div>
<!-- Sidebar principal -->
<div id="dashboardSidebar" class="sidebar">
    <!-- Botões flutuantes de controle -->
    <div id="sidebarControls" class="sidebar-controls">
        <button id="openSidebarBtn" class="sidebar-toggle" aria-label="Abrir Menu">
            <i id="sidebarToggleIcon" class="sidebarToggleIcon fas fa-bars"></i>
        </button>
        <button id="fixSidebarBtn" class="sidebar-fix-btn" aria-label="Fixar Sidebar" title="Fixar Sidebar">
            <i class="fas fa-thumbtack"></i>
        </button>
        <button id="toggleSidebarBtn" class="sidebar-toggle-btn" aria-label="Alternar Sidebar">&lt;</button>
    </div>

    <div class="logoCenter">
        <img src="../public/assets/img/favicon.ico" alt="Logo.png">
    </div>

    <!-- Menu de navegação -->
    <div class="sidebar-menu">

        <h3>
            <i class="fas fa-tachometer-alt"></i>
            <span class="sidebar-title">Painel</span>
        </h3>

        <!-- Menu Admin -->
        <?php if ($_SESSION['nivel_acesso'] == OPERADOR): ?>
            <fieldset>
                <?php $page = $_GET['page'] ?? ''; ?>
                <a href="dashboard.php?page=main-operador" class="<?= $page == 'main' ? 'active' : '' ?>">
                    <i class="fas fa-home"></i> <span class="link-text">Página Inicial</span>
                </a>
            </fieldset>
        <?php endif; ?>

        <!-- Menu Admin -->
        <?php if ($_SESSION['nivel_acesso'] == ADMIN): ?>
            <fieldset>
                <?php $page = $_GET['page'] ?? ''; ?>
                <a href="dashboard.php?page=main-admin" class="<?= $page == 'main' ? 'active' : '' ?>">
                    <i class="fas fa-home"></i> <span class="link-text">Página Inicial</span>
                </a>
            </fieldset>

            <fieldset id="adminModule">
                <legend>Modulo Administrador</legend>
                <a href="dashboard.php?page=veiculos" class="<?= $page == 'veiculos' ? 'active' : '' ?>">
                    <i class="fas fa-car-alt"></i> <span class="link-text">Cadastrar Veículos</span>
                </a>
                <a href="dashboard.php?page=newUser" class="<?= $page == 'newUser' ? 'active' : '' ?>">
                    <i class="fas fa-users"></i> <span class="link-text">Cadastrar Usuário</span>
                </a>
            </fieldset>
        <?php endif; ?>

    </div>

    <!-- Rodapé com botão de logout e versão -->
    <div class="sidebar-footer">
        <a href="../src/includes/auth/logout.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i> <span class="link-text">Sair</span>
        </a>
        <div class="app-version">v1.0.0</div>
    </div>
</div>