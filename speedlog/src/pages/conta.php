<h3>Alterar Senha</h3>
<form action="dashboard.php" method="POST">
    <input type="password" name="new_password" placeholder="Nova senha (mínimo 3 caracteres)" required><br>
    <button type="submit">Alterar Senha</button>
</form>

<h3>Alterar E-mail</h3>
<form action="dashboard.php" method="POST">
    <input type="email" name="new_email" placeholder="Novo e-mail" required><br>
    <button type="submit">Alterar E-mail</button>
</form>

<h3>Excluir Conta</h3>
<form action="dashboard.php" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir sua conta? Esta ação é irreversível.');">
    <button type="submit" name="delete_account" style="background-color:red;color:white;">Excluir Conta</button>
</form>
