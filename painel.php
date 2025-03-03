<?php
include 'config/includes.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dragon Quests</title>
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">
</head>

<body class="pagePainel">
    <!-- HEADER/NAVBAR -->
    <header id="header">

        <!-- Abrir Menu Mobile -->
        <button class="openMenu gradient-dourado" id="openMenu" onclick="sideBar()">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-list"
                viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
            </svg>
        </button>
        <!-- SideBar/Navigation -->
        <div class="headerNav">
            <!-- Navegador de Paginas -->
            <nav id="headerMenu">

                <!-- Fechar Menu Mobile -->
                <button class="closeMenu gradient-dourado" onclick="sideBar()">
                    Fechar
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x"
                        viewBox="0 0 16 16">
                        <path
                            d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
                    </svg>
                </button>

                <a>Home</a>
                <a id="sobreMim">Sobre</a>
                <a id="servicosWeb">Serviços</a>
                <a id="meuContato">Contato</a>
                <a class="active" id="meuContato">Usuário</a>
                <a href="dragon_world/index.html">Lineage World</a>


                <!-- Abrir Menu Acessibilidade -->
                <button class="openAcess" onclick="showAcess()">
                    ♿
                </button>
                <!-- Menu Acessibilidade -->
                <!-- <div class="acessibilidade">
                        <p>teste</p>
                        <ul>
                            <li class="zoomInBtn" onclick="zoomIn()"></li>
                            <li class="zoomOutBtn" onclick="zoomOut()"></li>
                            <li id="DarkBtn" onclick="darkMode()"></li>
                        </ul>  
                    </div> -->
            </nav>

        </div>


    </header>

    <main onclick="sideBarClose()">
        <!-- LOGIN -->
        <section>

            <div class="painelForm" id="loginForm">
                <?php if (isset($_SESSION['user_id'])): ?>

                    <!-- Usuário logado -->
                    <div class="userLogged">
                        <p>Bem-vindo, <?php echo $_SESSION['user_id']; ?>!</p>
                        <a href="dashboard/dashboard.php">Painel</a> |
                        <a href="config/logout.php">Sair</a>
                    </div>
                <?php else: ?>

                    <!-- Formulário de login -->
                    <form action="config/login.php" method="POST">
                        <fieldset>
                            <legend>Painel</legend>
                            <label>Login:</label>
                            <input type="text" name="login" required>

                            <label>Senha:</label>
                            <input type="password" name="password" required>

                            <button type="submit">Entrar</button>
                        </fieldset>
                    </form>
                    <div class="userRegister">
                        Não tem uma conta?<a onclick="toggleForm()"> Cadastre-se</a>
                    </div>
                <?php endif; ?>
            </div>

            </div>

            <!-- CADASTRO -->
            <div class="painelForm" id="regForm">
                <form action="config/register.php" method="post">
                    <fieldset>
                        <legend>Cadastro</legend>
                        <label for="nome">Usuário</label>
                        <label>Login:</label>
                        <input type="text" name="login" required>

                        <label>Senha:</label>
                        <input type="password" name="password" required>

                        <button type="submit">Registrar</button>
                        <a onclick="toggleForm()">Ja é Cadastrado? Faça Login</a>
                    </fieldset>
                </form>


            </div>
        </section>
    </main>

    <script>
        var loginForm = document.getElementById("loginForm");
        var regForm = document.getElementById("regForm");
        var showLoginForm = true;  // Começa mostrando o login

        function toggleForm() {
            showLoginForm = !showLoginForm;

            if (showLoginForm) {
                loginForm.style.display = "block";
                regForm.style.display = "none";
            } else {
                loginForm.style.display = "none";
                regForm.style.display = "block";
            }
        }

        // Definindo o estado inicial
        loginForm.style.display = "block";
        regForm.style.display = "none";


    </script>

    <script src="../assets/js/script.js"></script>

    <script src="../assets/js/Acessibilidade.js"></script>
</body>

</html>