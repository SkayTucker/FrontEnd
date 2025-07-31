<?php
session_start();
require '../../app/config/includes.php';

$MAX_ATTEMPTS = 3;
$BLOCK_TIME = 60; // segundos

$ip = $_SERVER['REMOTE_ADDR'];

// Garante que é um array de tentativas por IP
if (!isset($_SESSION['login_attempts']) || !is_array($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = [];
}

if (!isset($_SESSION['login_attempts'][$ip])) {
    $_SESSION['login_attempts'][$ip] = ['count' => 0, 'block_until' => 0];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attempt = &$_SESSION['login_attempts'][$ip];

    // Verifica bloqueio temporário
    if (time() < $attempt['block_until']) {
        $_SESSION['modal_title'] = 'Acesso Bloqueado';
        $_SESSION['modal_message'] = "Você excedeu o número de tentativas. Tente novamente em " . ceil(($attempt['block_until'] - time()) / 60) . " minuto(s).";
        header("Location: ../../../index.php");
        exit();
    }

    // Sanitização simples
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Busca usuário
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Login bem-sucedido
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['nivel_acesso'] = $user['nivel_acesso'];

        // Limpa tentativas após sucesso
        unset($_SESSION['login_attempts'][$ip]);

        header("Location: ../../../public/dashboard.php");
        exit();
    } else {
        // Falha no login
        $attempt['count']++;

        if ($attempt['count'] >= $MAX_ATTEMPTS) {
            $attempt['block_until'] = time() + $BLOCK_TIME;
            $_SESSION['modal_title'] = 'Bloqueado';
            $_SESSION['modal_message'] = "Muitas tentativas incorretas. Seu IP foi bloqueado por 1 minuto.";
        } else {
            $restantes = $MAX_ATTEMPTS - $attempt['count'];
            $_SESSION['modal_title'] = 'Erro de Acesso';
            $_SESSION['modal_message'] = "Email ou senha incorretos. Tentativas restantes: $restantes.";
        }

        header("Location: ../../../index.php");
        exit();
    }
}
