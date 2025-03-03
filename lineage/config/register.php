<?php
session_start();

include 'includes.php';
include 'base64.php';

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $password = trim($_POST['password']);
    $ip = $_SERVER['REMOTE_ADDR'];

    // Validação básica
    if (strlen($login) < 2 || strlen($login) > 14) {
        echo "O login deve ter entre 2 e 14 caracteres.";
        exit();
    }

    if (strlen($password) < 4) {
        echo "A senha deve ter pelo menos 4 caracteres.";
        exit();
    }

    // Codifica a senha em Base64
    //$hashedPassword = encodeBase64(hash("sha1", $password, true)); // SHA1 e Base64 como no L2J
    $hashedPassword = encodeBase64(hash("sha1", $password, true));


    // Verifica se o usuário já existe
    $stmt = $conn->prepare("SELECT login FROM accounts WHERE login = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Este login já está em uso.";
        exit();
    }
    $stmt->close();

    // Insere no banco de dados
    $stmt = $conn->prepare("INSERT INTO accounts (login, password, lastactive, accessLevel, lastIP) VALUES (?, ?, ?, ?, ?)");
    $lastActive = time() * 1000; // Tempo em milissegundos
    $accessLevel = 0;

    $stmt->bind_param("sssis", $login, $hashedPassword, $lastActive, $accessLevel, $ip);
    
    if ($stmt->execute()) {
        echo "Conta criada com sucesso!";
        header("Location: login.php"); // Redireciona para login
    } else {
        echo "Erro ao criar conta.";
    }

    $stmt->close();
}
?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Cadastro de Conta</h2>
        <form action="register.php" method="POST">
            <label>Login:</label>
            <input type="text" name="login" required>

            <label>Senha:</label>
            <input type="password" name="password" required>

            <button type="submit">Registrar</button>
        </form>
    </div>
</body>
</html>
