<?php
require_once 'config/config.php';
session_start();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Tangerine">
    <link rel="shortcut icon" href="assets/img/pvpfull.png" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link    rel="stylesheet"    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"  />
    <title>Lineage ][ PvP Full</title>
</head>
<body>
    <div class="main">

        <!-- bg com animação -->
        <div class="bg-image animate__animated animate__fadeIn"></div>

        <div class="content">   
            <div class="site">
                <div class="header">
                <div class="logo" >
                            <img src="./assets/img/logo.png" alt="" srcset="">
                        </div>               
                </div>     
            </div>
        </div>





        <aside class="container server-menu corPrincipal">
            <ul class="navMenu">
                <li><button class="navItem" onclick="loadPage('home')"> 🏠 Home</button></li>
                <!-- <li><button onclick="loadPage('sobre')"> 📜Sobre</button></li> -->
                <li><button class="navItem"  onclick="loadPage('jogar')">  🎮Jogar</button></li>
                <!-- <li><button onclick="loadPage('contato')"> 📲Contato</button></li>           -->

                <?php if (isset($_SESSION['user'])) : ?>
                    <!-- Usuário está autenticado, exibe o painel do usuário -->
                    <li>
                        <button class="navItem" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRightUser" aria-controls="offcanvasRightUser">
                            👤 Usuário
                        </button>

                        <!-- Offcanvas -->
                        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRightUser" aria-labelledby="offcanvasRightLabelUser">
                            <div class="offcanvas-header corPrincipal corSecundaria">
                                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body">
                                <h5>Bem-vindo, <?php echo htmlspecialchars($_SESSION['user']['login']); ?>!</h5>
                                <!-- Adicione aqui o conteúdo do painel do usuário -->
                                <ul>
                                    <li><a href="#">Perfil</a></li>
                                    <li><a href="#">Configurações</a></li>
                                    <li><a href="./config/includes/logout.php">Sair</a></li>
                                </ul>
                            </div>
                        </div>
                    </li>
                <?php else : ?>
                    <!-- Usuário não está autenticado, exibe o formulário de login -->
                    <li>
                        <button class="navItem" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRightLogin" aria-controls="offcanvasRightLogin">
                            👤 Login
                        </button>

                        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRightLogin" aria-labelledby="offcanvasRightLabelLogin">
                        <div class="offcanvas-header offheader">
                                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body">
                                <?php if (isset($_SESSION['user'])) : ?>
                                    <!-- Usuário está autenticado, exibe mensagem de boas-vindas e botão de logout -->
                                    <h5>Bem-vindo, <?php echo htmlspecialchars($_SESSION['user']['login']); ?>!</h5>
                                    <ul>
                                        <li><a href="#">Perfil</a></li>
                                        <li><a href="#">Configurações</a></li>
                                        <li><a href="./config/includes/logout.php">Sair</a></li>
                                    </ul>
                                <?php else : ?>
                                    <!-- Usuário não está autenticado, exibe o formulário de login -->
                                    <form id="loginForm" method="post" action="./config/includes/processa_login.php">
                                        <label for="login">Login:</label>
                                        <input type="text" name="login" required><br>

                                        <label for="password">Senha:</label>
                                        <input type="password" name="password" required><br>

                                        <input type="submit" value="Login">
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </li>
                <?php endif; ?>
            </ul>
        </aside>



        <div id="content-site" class="container bg-dark">        
        <div class="players"><?php include 'config/rank.php'; ?></div>
            <main class="mainSpa" id="main-content"></main>
        </div>


        

    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const offcanvasBodyUser = document.getElementById('offcanvasRightUser').querySelector('.offcanvas-body');
            const offcanvasBodyLogin = document.getElementById('offcanvasRightLogin').querySelector('.offcanvas-body');

            // Se a sessão do usuário estiver definida, atualize os off-canvas
            if (<?= isset($_SESSION['user']) ? 'true' : 'false' ?>) {
                const userName = <?= isset($_SESSION['user']['login']) ? json_encode(htmlspecialchars($_SESSION['user']['login'])) : '""' ?>;
                offcanvasBodyUser.innerHTML = `
                    <h5>Bem-vindo, ${userName}!</h5>
                    <ul>
                        <li><a href="#">Perfil</a></li>
                        <li><a href="#">Configurações</a></li>
                        <li><a href="./config/includes/logout.php">Sair</a></li>
                    </ul>
                `;

                offcanvasBodyLogin.innerHTML = `
                    <h5>Bem-vindo, ${userName}!</h5>
                    <ul>
                        <li><a href="#">Perfil</a></li>
                        <li><a href="#">Configurações</a></li>
                        <li><a href="./config/includes/logout.php">Sair</a></li>
                    </ul>
                `;
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const offcanvasBodyUser = document.getElementById('offcanvasRightUser').querySelector('.offcanvas-body');
            const offcanvasBodyLogin = document.getElementById('offcanvasRightLogin').querySelector('.offcanvas-body');
            
            // Processa a mensagem de sucesso ou erro e exibe alerta correspondente
            const successMessage = <?= isset($_SESSION['success']) && $_SESSION['success'] ? json_encode(htmlspecialchars($_SESSION['message'])) : '""' ?>;
            const errorMessage = <?= isset($_SESSION['success']) && !$_SESSION['success'] ? json_encode(htmlspecialchars($_SESSION['message'])) : '""' ?>;

            if (successMessage) {
                alert(successMessage);
            } else if (errorMessage) {
                alert(errorMessage);
            }

            // Limpa as mensagens da sessão
            <?php unset($_SESSION['success'], $_SESSION['message']); ?>
        });
    </script>    

    <script src="assets/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>
