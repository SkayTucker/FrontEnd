<?php
require_once "config/includes.php";
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Lineage ][ Elite - Cadastro</title>
    <link rel="shortcut icon" href="assets/css/img/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="assets/css/header.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/pages.css" />
    <link rel="stylesheet" href="assets/css/infoCards.css" />
    <script src="./pageNavigation.js" defer></script>
    <script src="./rankNavigation.js" defer></script>
    <script src="./register.js" defer></script>
</head>

<body>
    <header>
        <div class="headerSite">
            <video id="video1" autoplay muted loop playsinline class="video active">
                <source src="assets/media/kamael.mp4" type="video/mp4" />
            </video>
            <video id="video2" autoplay muted loop playsinline class="video">
                <source src="assets/media/portal.mp4" type="video/mp4" />
            </video>
        </div>
    </header>

    <div class="logoElite">
        <img src="assets/img/logo-elite.png" alt="Logo" />
    </div>

    <main>
        <div class="container">
            <nav class="navElite">
                <div class="topoLeft"></div>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="#">Register</a></li>
                </ul>
                <div class="topoRight"></div>
            </nav>
            <div class="figureRight">
                <img src="assets/img/bow.png" alt="" />
            </div>
        </div>

        <div class="mainContainer fadeIn" style="width: 400px;">
            <div id="pageContent">
                <!-- Conteúdo principal: Formulário de registro -->
                <div class="register-container" style="width:100%; background:#222; border-radius:8px; color:#eee; display:flex; flex-direction:column; align-items:center; justify-content:center;text-align:center;">
                    <h2>Cadastro de Conta</h2>
                    <form id="registerForm" novalidate>
                        <label for="login">Login:</label>
                        <input
                            type="text"
                            id="login"
                            name="login"
                            required
                            minlength="2"
                            maxlength="14"
                            autocomplete="username"
                            style="width: 100%; padding: 0.5em; margin-bottom: 0.8em;" />

                        <label for="password">Senha:</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            minlength="4"
                            autocomplete="new-password"
                            style="width: 100%; padding: 0.5em; margin-bottom: 0.8em;" />

                        <label for="confirm_password">Confirmar Senha:</label>
                        <input
                            type="password"
                            id="confirm_password"
                            name="confirm_password"
                            required
                            minlength="4"
                            autocomplete="new-password"
                            style="width: 100%; padding: 0.5em; margin-bottom: 1em;" />

                        <button
                            type="submit"
                            id="submitBtn"
                            style="width: 100%; padding: 0.7em; background-color: #4caf50; color: white; border: none; border-radius: 4px; font-size: 1.1em;">Registrar</button>
                    </form>

                    <div id="message" style="margin-top: 1em; font-weight: bold;"></div>
                </div>
            </div>

        </div>
    </main>

    <footer>
        <h5>L2 Elite - DragonDev @ 2025</h5>
    </footer>

    <script>
        (() => {
            const form = document.getElementById("registerForm");
            const loginInput = form.login;
            const passwordInput = form.password;
            const confirmInput = form.confirm_password;
            const messageDiv = document.getElementById("message");
            const submitBtn = document.getElementById("submitBtn");

            const showMessage = (text, isError = true) => {
                messageDiv.textContent = text;
                messageDiv.style.color = isError ? "red" : "green";
            };

            const clearMessage = () => {
                messageDiv.textContent = "";
            };

            const validateForm = () => {
                const login = loginInput.value.trim();
                const password = passwordInput.value;
                const confirmPassword = confirmInput.value;

                if (login.length < 2 || login.length > 14) {
                    showMessage("O login deve ter entre 2 e 14 caracteres.");
                    loginInput.focus();
                    return false;
                }

                if (password.length < 4) {
                    showMessage("A senha deve ter pelo menos 4 caracteres.");
                    passwordInput.focus();
                    return false;
                }

                if (password !== confirmPassword) {
                    showMessage("As senhas não coincidem.");
                    confirmInput.focus();
                    return false;
                }

                clearMessage();
                return true;
            };

            const submitForm = async (event) => {
                event.preventDefault();

                if (!validateForm()) return;

                submitBtn.disabled = true;
                showMessage("Registrando conta...", false);

                const formData = new FormData(form);

                try {
                    const response = await fetch("config/register.php", {
                        method: "POST",
                        body: formData,
                    });

                    const text = await response.text();

                    if (text.trim() === "OK") {
                        showMessage("Conta criada com sucesso!", false);
                        form.reset();
                        loginInput.focus();
                    } else {
                        showMessage(text, true);
                    }
                } catch (error) {
                    showMessage("Erro ao comunicar com o servidor.", true);
                    console.error(error);
                } finally {
                    submitBtn.disabled = false;
                }
            };

            form.addEventListener("submit", submitForm);
        })();
    </script>
</body>

</html>