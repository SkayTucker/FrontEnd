<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes.php';
include 'base64.php';

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $password = trim($_POST['password']);

    // Codifica a senha informada
    $hashedPassword = encodeBase64(hash("sha1", $password, true));

    // Busca a conta no banco de dados
    $stmt = $conn->prepare("SELECT login FROM accounts WHERE login = ? AND password = ?");
    $stmt->bind_param("ss", $login, $hashedPassword);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['user_id'] = $login;
        header("Location: ../index.php");
        exit();
    } else {
        echo  "<script>alert('Usuário ou senha Incorretos');</script>";
        echo "<script>javascript:window.location='../index.php';</script>";
    }
    $stmt->close();
}
?>
