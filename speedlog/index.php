<?php
require 'src/app/config/includes.php';

$tempoRestante = 0;
$ip = $_SERVER['REMOTE_ADDR'];

// Verifica se o IP está bloqueado na sessão
if (isset($_SESSION['login_attempts'][$ip])) {
    $attempt = $_SESSION['login_attempts'][$ip];
    if (time() < $attempt['block_until']) {
        $tempoRestante = $attempt['block_until'] - time();
    }
}

// Redireciona usuário logado
if (!empty($_SESSION['user_id'])) {
    header("Location: public/dashboard.php");
    exit;
}

// Captura e limpa mensagens da modal
$modalTitle = $_SESSION['modal_title'] ?? null;
$modalMessage = $_SESSION['modal_message'] ?? null;
unset($_SESSION['modal_title'], $_SESSION['modal_message']);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="public/assets/img/favicon.ico" type="image/x-icon" />

    <!-- Open Graph -->
    <meta property="og:title" content="Logística | Speedinfo" />
    <meta property="og:description" content="Speedlog logística é uma plataforma de gestão de logística e transporte, oferecendo soluções eficientes para otimizar processos e melhorar a performance do seu negócio." />
    <meta property="og:image" content="https://speedinfotelecom.com.br/public/assets/img/favicon.ico" />
    <meta property="og:url" content="https://speedinfotelecom.com.br/" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="Speedlog Logística" />

    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/f5de20681c.js" crossorigin="anonymous" defer></script>

    <!-- CSS -->
    <link rel="stylesheet" href="public/assets/css/global.css" />
    <link rel="stylesheet" href="public/assets/css/style.css" />
    <link rel="stylesheet" href="public/assets/css/responsive.css" />

    <title>Logística | Speedinfo</title>
</head>

<body>
    <main class="container gradient login" role="main" aria-label="Área de login">
        <div class="logoStart fadeIn" aria-label="Logo Speedlog">
            <img src="public/assets/img/favicon.ico" alt="Logo Speedlog" width="200" height="auto" />
        </div>

        <section class="auth-container fadeIn" aria-labelledby="login-title">
            <h1 id="login-title ">Credenciais</h1>
            <div class="login">
                <?php if ($tempoRestante > 0): ?>
                    <div id="loginBlocked" style="background: #ffcccc; color: #990000; padding: 1rem; margin-bottom: 1rem; border-radius: 5px;">
                        <strong>Você está temporariamente bloqueado.</strong><br>
                        Tente novamente em <span id="contadorBloqueio"></span>.
                    </div>

                    <script>
                        let segundosRestantes = <?= $tempoRestante ?>;
                        const contador = document.getElementById('contadorBloqueio');
                        const caixaBloqueio = document.getElementById('loginBlocked');

                        function atualizarContador() {
                            if (segundosRestantes <= 0) {
                                contador.innerText = "agora!";

                                // Altera a cor da caixa para verde
                                caixaBloqueio.style.backgroundColor = "#ccffcc";
                                caixaBloqueio.style.color = "#006600";
                                caixaBloqueio.innerHTML = `
                <strong>Você já pode tentar novamente.</strong><br>
                Por favor, preencha seus dados e tente o login.
            `;
                                return;
                            }

                            const minutos = Math.floor(segundosRestantes / 60);
                            const segundos = segundosRestantes % 60;
                            contador.innerText = `${minutos}m ${segundos < 10 ? '0' : ''}${segundos}s`;

                            segundosRestantes--;
                            setTimeout(atualizarContador, 1000);
                        }

                        atualizarContador();
                    </script>

                <?php endif; ?>

                <form class="" action="src/includes/auth/login.php" method="post" novalidate>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="✉ Email" required aria-required="true" autocomplete="email" />

                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" placeholder="🗝 Senha" required aria-required="true" autocomplete="current-password" />

                    <button type="submit" aria-label="Entrar">Entrar</button>
                    <a href="#" tabindex="0">Esqueceu a senha?</a>
                </form>
            </div>
        </section>
    </main>

    <?php if ($modalTitle && $modalMessage): ?>
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                alert("<?= htmlspecialchars($modalTitle, ENT_QUOTES, 'UTF-8') ?>\n\n<?= htmlspecialchars($modalMessage, ENT_QUOTES, 'UTF-8') ?>");
            });
        </script>
    <?php endif; ?>
</body>

</html>