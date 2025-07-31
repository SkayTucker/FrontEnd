<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Cadastro de Conta - L2 Elite</title>
<style>
    .register-container {
        max-width: 360px;
        margin: 40px auto;
        padding: 1em;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-family: Arial, sans-serif;
        background: #f9f9f9;
    }
    label {
        display: block;
        margin-top: 1em;
        font-weight: bold;
    }
    input {
        width: 100%;
        padding: 0.4em;
        font-size: 1em;
        box-sizing: border-box;
    }
    button {
        margin-top: 1.5em;
        width: 100%;
        padding: 0.7em;
        font-size: 1.1em;
        background: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    button:disabled {
        background: #7baaf7;
        cursor: not-allowed;
    }
    #message {
        margin-top: 1em;
        min-height: 1.5em;
        font-weight: bold;
    }
</style>
</head>
<body>

<div class="register-container">
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
        />

        <label for="password">Senha:</label>
        <input
            type="password"
            id="password"
            name="password"
            required
            minlength="4"
            autocomplete="new-password"
        />

        <label for="confirm_password">Confirmar Senha:</label>
        <input
            type="password"
            id="confirm_password"
            name="confirm_password"
            required
            minlength="4"
            autocomplete="new-password"
        />

        <button type="submit" id="submitBtn">Registrar</button>
    </form>

    <div id="message"></div>
</div>

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
