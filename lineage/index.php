<?php
require_once "config/includes.php";
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lineage ][ Elite</title>
    <link rel="shortcut icon" href="assets/css/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/pages.css">
    <link rel="stylesheet" href="assets/css/infoCards.css">
    <script src="./pageNavigation.js" defer></script>
    <script src="./rankNavigation.js" defer></script>
    <script src="./register.js" defer></script>
</head>

<body>
    <header>
        <div class="headerSite">
            <video id="video1" autoplay muted loop playsinline class="video active">
                <source src="assets/media/kamael.mp4" type="video/mp4">
            </video>
            <video id="video2" autoplay muted loop playsinline class="video">
                <source src="assets/media/portal.mp4" type="video/mp4">
            </video>
        </div>
    </header>

    <div class="logoElite">
        <img src="assets/img/logo-elite.png" alt="Logo">
    </div>

    <main>
        <!-- Barra Menu L2Elite Navegação paginas Gerais -->
        <div class="container">
            <nav class="navElite">
                <div class="topoLeft"></div>
                <ul>
                    <li><a href="#" class="navElite-link" data-content="home.php">Home</a></li>
                    <li><a href="#" class="navElite-link" data-content="sobre.php">Info</a></li>
                    <li><a href="register.php">Register</a></li>
                    <li><a href="#" class="navElite-link" data-content="download.php">Download</a></li>
                </ul>
                <div class="topoRight"></div>
            </nav>
            <div class="figureRight">
                <img src="assets/img/bow.png" alt="">
            </div>
        </div>

        <div class="mainContainer fadeIn">
            <aside class="menuLeft">
                <div class="userLogin">
                    <div class="eliteBtn">
                        <h2>User Panel</h2>
                    </div>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="userLogged">
                            <p>Bem-vindo, <?php echo $_SESSION['user_id']; ?>!</p>
                            <a target="_blank" href="painel/dashboard.php">Painel</a> |
                            <a href="config/logout.php">Sair</a>
                        </div>
                    <?php else: ?>
                        <form action="config/login.php" method="POST">
                            <div class="userLogin-content">
                                <input type="text" name="login" placeholder=" 🎫 Account" required>
                                <input type="password" name="password" placeholder="🔑 Password" required>
                            </div>
                            <button class="eliteBtnSmall" type="submit">
                                <h1>Login</h1>
                            </button>
                        </form>
                        <div class="userRegister">
                            Não tem uma conta? <a href="register.php">Cadastre-se</a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="services">
                    <div class="eliteBtn">
                        <h2>Services</h2>
                    </div>
                    <ul>
                        <li><a class="heroBtn rank-link" data-rank="rank/olympiad.php" href="#"></a></li>
                        <li><a class="bossBtn rank-link" data-rank="rank/raidboss.php" href="#"></a></li>
                        <li><a class="castleBtn rank-link" data-rank="rank/castle.php" href="#"></a></li>
                        <li><a class="clanBtn rank-link" data-rank="rank/clan.php" href="#"></a></li>
                        <li><a class="pvpBtn rank-link" data-rank="rank/pvp.php" href="#"></a></li>
                    </ul>

                </div>

                <div class="RankPlayers">
                    <div class="eliteBtn">
                        <h2>Ranking</h2>
                    </div>
                    <div class="rankTable fadeInSlow" id="rankTable">
                        <p>Conteúdo estático de teste</p>
                    </div>

                </div>
            </aside>

            <div id="pageContent"></div>

            <aside class="menuRight">
                <div class="playersOnline">
                    <div class="eliteBtn">
                        <h2>Server Status</h2>
                    </div>
                    <div class="serverStatus">
                        <div class="loginServer">
                            Login:
                            <?php echo ($loginserver >= 1) ? '<img src="assets/img/online.png" width="48" height="16">' : '<img src="assets/img/offline.png" width="48" height="16">'; ?>
                        </div>
                        <div class="gameServer">
                            Game:
                            <?php echo ($gameserver >= 1) ? '<img src="assets/img/online.png" width="48" height="16">' : '<img src="assets/img/offline.png" width="48" height="16">'; ?>
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
                </div>

                <iframe src="https://discord.com/widget?id=526601733095424004&theme=dark" width="100%" height="400"
                    allowtransparency="true" frameborder="0"
                    sandbox="allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts"></iframe>
            </aside>
        </div>

    </main>

    <section class="info-cards container">
        <div class="card">
            <h3>📜 Patchnotes</h3>
            <p>Fique por dentro das últimas atualizações e melhorias do servidor.</p>
            <a href="patchnotes.php" class="btn">Ver Patchnotes</a>
        </div>
        <div class="card">
            <h3>📚 Wiki</h3>
            <p>Explore guias detalhados e informações essenciais sobre o jogo.</p>
            <a href="wiki.php" class="btn">Acessar Wiki</a>
        </div>
        <div class="card">
            <h3>🎓 Tutorial</h3>
            <p>Aprenda a jogar do zero com nosso tutorial completo e fácil de entender.</p>
            <a href="tutorial.php" class="btn">Ver Tutorial</a>
        </div>
    </section>

    <footer>
        <h5>L2 Elite - DragonDev @ 2025</h5>
    </footer>
</body>

</html>