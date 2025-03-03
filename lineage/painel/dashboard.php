<?php
session_start();
include '../config/includes.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lineage 2 Dashboard</title>
    <link rel="stylesheet" href="css/styleDashboard.css">
    <script src="js/spa.js" defer></script>
    <script>
        document.getElementById("btnExchangeDogecoin").addEventListener("click", function () {
            let wallet = document.getElementById("wallet").value;
            if (!wallet) {
                alert("Por favor, insira um endereço de carteira Dogecoin.");
                return;
            }

            fetch("api/exchange_dogecoin.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "wallet_address=" + encodeURIComponent(wallet)
            })
                .then(response => response.text())
                .then(data => {
                    document.getElementById("exchangeMessage").textContent = data;
                })
                .catch(error => console.error("Erro ao solicitar Dogecoin:", error));
        });
    </script>

</head>

<body>

    <header>
        <div class="logo">LOGO</div>

        <div class="serverStatus">
            <div class="loginServer">
                Login:
                <?php
                if ($loginserver >= 1) {
                    echo '<img src="../assets/img/online.png" width="48" height="16">';
                } else {
                    echo '<img src="../assets/img/offline.png" width="48" height="16">';
                }

                ?>
            </div>
            <div class="gameServer">
                Game:
                <?php

                if ($gameserver >= 1) {

                    echo '<img src="../assets/img/online.png" width="48" height="16">';
                } else {
                    echo '<img src="../assets/img/offline.png" width="48" height="16">';
                }

                ?>
            </div>
        </div>

        <div class="siteCoins">
            Coins do Site:
            <?php include 'api/get_site_coins.php'; ?>
        </div>
        <div class="exchangeDogecoin">
            Dogecoins:XX
            <?php include 'pages/dogecoin.php' ?>
        </div>

        <a href="logout.php">Sair</a>
    </header>

    <div class="bgMain">
        <div class="overlay"></div>
    </div>

    <main>
        <div class="container">
            <div id="content">
                <!-- O conteúdo das páginas será carregado aqui -->
            </div>
        </div>

        <section class="siteMenu">
            <ul>
                <li><a href="#" data-page="home">Home</a></li>
                <li><a href="#" data-page="characters">Personagens</a></li>
                <li><a href="#" data-page="configuracoes">Configurações</a></li>
                <li><a class="favorite" href="#" data-page="download">Download</a></li>
            </ul>
        </section>
    </main>




</body>

</html>