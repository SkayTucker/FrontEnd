<div class="container">
    <section class="newUserPage">
        <!-- Formulário de Cadastro -->
        <fieldset>
            <legend>Cadastrar Novo Usuário</legend>
            <form id="form-cadastrar-user" action="../src/includes/auth/registerUser.php" method="post" class="user-register-form">
                <div class="form-group">
                    <input type="text" name="name" placeholder="Nome completo" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Senha" required>
                    <select name="nivel_acesso" required>
                        <option value="" disabled selected>Selecione o nível de acesso</option>
                        <option value="Operador">Operador</option>
                        <option value="Administrador">Administrador</option>
                    </select>
                    <button type="submit">Cadastrar</button>
                </div>
            </form>
        </fieldset>

        <!-- Lista de Usuários -->
        <h2>Usuários Cadastrados</h2>
        <table id="userTable">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Nível</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

    </section>

    <!-- Script -->
    <script>
        // Submissão do formulário de cadastro
        document.getElementById("form-cadastrar-user").addEventListener("submit", async function(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: "POST",
                    body: formData
                });

                const result = await response.json();

                alert(result.message);
                if (result.status === "success") {
                    form.reset();
                    carregarUsuarios();
                }

            } catch (error) {
                console.error(error);
                alert("Erro ao enviar requisição.");
            }
        });

        // Carrega a lista de usuários
        async function carregarUsuarios() {
            try {
                const res = await fetch("../src/includes/auth/getUsers.php");
                const data = await res.json();

                const tbody = document.querySelector("#userTable tbody");
                tbody.innerHTML = "";

                if (data.status === "success") {
                    data.users.forEach(user => {
                        const tr = document.createElement("tr");
                        tr.innerHTML = `
                        <td data-label="Nome">${user.name}</td>
                        <td data-label="Email">${user.email}</td>
                        <td data-label="Nível">${user.nivel_acesso === 0 ? "Administrador" : "Operador"}</td>
                        <td data-label="Ações">
                            <button onclick="alterarSenha(${user.id}, '${user.name}')">Alterar Senha</button>
                            <button onclick="excluirUsuario(${user.id}, '${user.name}')">Excluir</button>
                        </td>
                        `;


                        tbody.appendChild(tr);
                    });
                } else {
                    alert("Erro ao carregar usuários: " + data.message);
                }
            } catch (err) {
                console.error(err);
                alert("Erro ao buscar usuários.");
            }
        }

        // Alterar senha
        async function alterarSenha(userId, userName) {
            const novaSenha = prompt(`Digite a nova senha para ${userName}:`);
            if (!novaSenha) return;

            const formData = new FormData();
            formData.append("id", userId);
            formData.append("password", novaSenha);

            try {
                const res = await fetch("/speedlog/src/includes/auth/updatePassword.php", {
                    method: "POST",
                    body: formData
                });


                const result = await res.json();
                alert(result.message);
            } catch (err) {
                alert("Erro ao alterar senha.");
                console.error(err);
            }
        }

        // Excluir usuário
        async function excluirUsuario(userId, userName) {
            if (!confirm(`Deseja realmente excluir o usuário ${userName}?`)) return;

            const formData = new FormData();
            formData.append("id", userId);

            try {
                const res = await fetch("../src/includes/auth/deleteUser.php", {
                    method: "POST",
                    body: formData
                });

                const result = await res.json();
                alert(result.message);
                if (result.status === "success") carregarUsuarios();
            } catch (err) {
                alert("Erro ao excluir usuário.");
                console.error(err);
            }
        }

        // Inicializa tabela ao carregar
        carregarUsuarios();
    </script>
</div>