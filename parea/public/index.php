<?php
include 'db/dbQueries.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dragon Quests</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">
</head>

<body>

    <main>
        <h1>PAREA TECH</h1>
        <!-- LOGIN -->
        <section>
            <div class="painelForm" id="loginForm">
                <form action="db/dbQueries.php" method="post">
                    <fieldset>
                        <legend>Painel</legend>
                        <label for="user">Login</label>
                        <input type="text" placeholder="Usuário" name="username" id="user" required>
                        <label for="pwd">Senha</label>
                        <input type="password" name="password" id="pwd" required>
                        <input type="hidden" name="action" value="login">
                        <br>
                        <input class="btnForm" type="submit" value="Acessar">
                        <a onclick="toggleForm()">Cadastre-se</a>
                    </fieldset>
                </form>
            </div>

            <!-- CADASTRO -->
            <div class="painelForm" id="regForm">
                <form action="db/dbQueries.php" method="post">
                    <fieldset>
                        <legend>Cadastro</legend>
                        <label for="nome">Usuário</label>
                        <input type="text" placeholder="Nome do Usuário" name="username" id="nome" required>
                        <label for="senha">Senha</label>
                        <input type="password" name="password" id="senha" required>
                        <input type="hidden" name="action" value="register">
                        <br>
                        <input type="submit" value="Criar Usuário" class="btnForm">
                        <a onclick="toggleForm()">Já é cadastrado? Faça login</a>
                    </fieldset>
                </form>
            </div>
        </section>
    </main>

    <script>
        var loginForm = document.getElementById("loginForm");
        var regForm = document.getElementById("regForm");

        function toggleForm() {
            loginForm.style.display = loginForm.style.display === "none" ? "block" : "none";
            regForm.style.display = regForm.style.display === "none" ? "block" : "none";
        }

        // Define o estado inicial
        loginForm.style.display = "block";
        regForm.style.display = "none";
    </script>

</body>

</html>