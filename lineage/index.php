<?php
include 'config/includes.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lineage 2</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <header>
        <div class="headerSite">
            <!-- Primeiro vídeo -->
            <video id="video1" autoplay muted loop class="video active">
                <source src="assets/media/talking.mp4" type="video/mp4">
            </video>
            <!-- Segundo vídeo -->
            <video id="video2" autoplay muted loop class="video">
                <source src="assets/media/kamael.mp4" type="video/mp4">
            </video>
        </div>


        <button id="btnToggleSideBar">Menu</button>
        <aside id="mainSideBar" class="mainSideBar">
            <ul>
                <li>Menu 1</li>
                <li>Menu 2</li>
                <li>Menu 3</li>
            </ul>
        </aside>



        <div class="userLogin">
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Usuário logado -->
                <div class="userLogged">
                    <p>Bem-vindo, <?php echo $_SESSION['user_id']; ?>!</p>
                    <a target="_blank" href="painel/dashboard.php">Painel</a> |
                    <a href="config/logout.php">Sair</a>
                </div>
            <?php else: ?>
                <!-- Formulário de login -->
                <form action="config/login.php" method="POST">
                    <label>Login:</label>
                    <input type="text" name="login" required>

                    <label>Senha:</label>
                    <input type="password" name="password" required>

                    <button type="submit">Entrar</button>
                </form>
                <div class="userRegister">
                    Não tem uma conta? <a href="register.php">Cadastre-se</a>
                </div>
            <?php endif; ?>
        </div>



        <div class="serverStatus">
            <div class="loginServer">
                Login:
                <?php
                if ($loginserver >= 1) {
                    echo '<img src="assets/img/online.png" width="48" height="16">';
                } else {
                    echo '<img src="assets/img/offline.png" width="48" height="16">';
                }

                ?>
            </div>
            <div class="gameServer">
                Game:
                <?php

                if ($gameserver >= 1) {

                    echo '<img src="assets/img/online.png" width="48" height="16">';
                } else {
                    echo '<img src="assets/img/offline.png" width="48" height="16">';
                }

                ?>
            </div>
            <div class="playersOnline">
                Players Online:
                <?php
                $result = $conn->query("SELECT COUNT(*) AS total_online FROM characters WHERE online = 1");
                $row = $result->fetch_assoc();
                echo $row['total_online'];
                ?>
            </div>
        </div>
    </header>


    <nav class="navHeader">
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">Objetivos</a></li>
            <li><a href="#">Bosses</a></li>
            <li><a href="#">Ranks</a></li>
        </ul>
    </nav>



    <main>
        <div class="rankPlayers">
            <nav>
                <ul>
                    <li><a href="#" class="rank-link" data-rank="pvp.php">PvP/Pk</a></li>
                    <li><a href="#" class="rank-link" data-rank="clan.php">Clan/Castle</a></li>
                    <li><a href="#" class="rank-link" data-rank="adena.php">Adena/Coin</a></li>
                    <li><a href="#" class="rank-link" data-rank="online.php">Online</a></li>
                </ul>
            </nav>

            <div class="rankTable" id="rankTable">
                <?php include 'config/rank/pvp.php'; // PvP como padrão ?>
            </div>



        </div>

        <div class="container">
            <?php include 'pages/home.php'; ?>
        </div>




    </main>


    <script>
        const sidebar = document.querySelector(".mainSideBar");
        const sidebarBtn = document.getElementById("btnToggleSideBar");

        sidebarBtn.addEventListener('click', () => {
            sidebar.classList.toggle('mainSideBarOpen');
        });

        document.addEventListener("DOMContentLoaded", function () {
            function loadPageRank(pageRank) {
                fetch("config/rank/" + pageRank)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById("rankTable").innerHTML = data;
                    })
                    .catch(error => console.error("Erro ao carregar a página:", error));
            }

            document.querySelectorAll(".rank-link").forEach(link => {
                link.addEventListener("click", function (e) {
                    e.preventDefault();
                    const page = this.getAttribute("data-rank");
                    loadPageRank(page); // Corrigido o nome da função
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            const video1 = document.getElementById("video1");
            const video2 = document.getElementById("video2");

            const videos = ["assets/media/kamael.mp4", "assets/media/talking.mp4"];
            let currentVideo = 0;

            function changeVideo() {
                // Alterna entre os vídeos
                currentVideo = (currentVideo + 1) % videos.length;

                // Define qual vídeo está visível
                if (video1.classList.contains("active")) {
                    video2.src = videos[currentVideo]; // Atualiza o vídeo oculto
                    video2.load();
                    video2.play();
                    video1.classList.remove("active");
                    video2.classList.add("active");
                } else {
                    video1.src = videos[currentVideo]; // Atualiza o vídeo oculto
                    video1.load();
                    video1.play();
                    video2.classList.remove("active");
                    video1.classList.add("active");
                }
            }

            // Troca de vídeo a cada 10 segundos
            setInterval(changeVideo, 10000);
        });

    </script>
</body>

</html>