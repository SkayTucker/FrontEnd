<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Speedlog</title>
    <link rel="stylesheet" href="../public/assets/css/responsive.css">
    <link rel="stylesheet" href="../public/assets/css/global.css">
    <link rel="stylesheet" href="../public/assets/css/style.css">
    <link rel="stylesheet" href="../public/assets/css/modal.css">
    <link rel="stylesheet" href="../public/assets/css/sidebar.css">
    <link rel="stylesheet" href="../public/assets/css/dashboard.css">
    <link rel="stylesheet" href="../public/assets/css/operador.css">
    <link rel="stylesheet" href="../public/assets/css/admin.css">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Scripts -->

    <script src="../public/assets/js/sidebar.js" defer></script>
    <script src="../public/assets/js/modal.js" defer></script>
    <!-- <script src="../public/assets/js/dashboard.js" defer></script> -->
    <!-- <script src="../public/assets/js/admin.js" defer></script> -->
    <script src="../public/assets/js/operador.js" defer></script>

</head>

<body class="gradient">
    <div id="modal-template" class="modal hidden" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <div class="modal-content">

            <!-- Cabeçalho do modal -->
            <header class="modal-header">
                <h2 id="modal-title" class="modal-title"></h2>
                <button id="close-modal" class="close-modal" aria-label="Fechar modal">×</button>
            </header>

            <!-- Corpo do modal -->
            <section id="modal-body" class="modal-body">
                <!-- Conteúdo dinâmico será carregado aqui -->
            </section>

        </div>
    </div>