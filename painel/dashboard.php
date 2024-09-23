<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    // Se não estiver logado, redireciona para a página de login
    header("Location: login.php");
    exit();
}

// Conteúdo protegido: aqui fica o código do dashboard
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Responsiva</title>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: Arial, sans-serif;
        }

        main {
            width: 100vw;
            height: 100vh;
            padding: 20px;
            box-sizing: border-box;
        }

        ul {
            display: flex;
            justify-content: space-around;
            list-style-type: none;
            padding: 0;
        }

        ul li a {
            text-decoration: none;
            color: black;
            font-size: 18px;
        }

        h2 {
            text-align: center;
            font-size: 24px;
        }

        @media (max-width: 600px) {
            ul {
                flex-direction: column; /* Torna a lista vertical em dispositivos móveis */
                align-items: center;
            }

            h2 {
                font-size: 20px; /* Diminui o tamanho do título em telas menores */
            }
        }
    </style>
</head>
<body>
    <main>
        <section class="sub-menu gradient-dourado">
            <ul class="gradient-primario">
                <li><a href="#">Ver Projetos</a></li>
                <li><a href="#">Ver Atividades</a></li>
                <li><a href="#">Ver Calendário</a></li>
                <li>    <p><a href="config./logout.php">Logout</a></p></li>
            </ul>
        </section>

        <section>
            <h2>Carregar Projetos Recents</h2>
            <h2>Carregar Atividades Recents</h2>
            <h2>Api de Calendário</h2>
        </section>
    </main>
</body>
</html>

