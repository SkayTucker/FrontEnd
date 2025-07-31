// Função para ativar o formulário de registro — chamar sempre que carregar o formulário no DOM
function initRegister() {
    const form = document.getElementById("registerForm");
    if (!form) return;

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

    form.addEventListener("submit", async (event) => {
        event.preventDefault();

        if (!validateForm()) return;

        submitBtn.disabled = true;
        showMessage("Registrando conta...", false);

        const formData = new FormData(form);

        try {
            const response = await fetch("./config/register.php", {
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
    });
}

// Para permitir chamada externa após injetar o formulário via AJAX
window.initRegister = initRegister;
