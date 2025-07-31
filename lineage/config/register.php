<?php
session_start();

header('Content-Type: text/plain; charset=utf-8');

include 'includes.php';   // Conexão $conn, etc
include 'base64.php';     // Função encodeBase64

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Método inválido.";
    exit;
}

$login = trim($_POST['login'] ?? '');
$password = trim($_POST['password'] ?? '');
$confirm_password = trim($_POST['confirm_password'] ?? '');
$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

// Validações backend
if (strlen($login) < 2 || strlen($login) > 14) {
    echo "O login deve ter entre 2 e 14 caracteres.";
    exit;
}

if (strlen($password) < 4) {
    echo "A senha deve ter pelo menos 4 caracteres.";
    exit;
}

if ($password !== $confirm_password) {
    echo "As senhas não coincidem.";
    exit;
}

// Codifica senha SHA1 + Base64 (padrão L2J)
$hashedPassword = encodeBase64(hash("sha1", $password, true));

// Verifica se login já existe
$stmt = $conn->prepare("SELECT login FROM accounts WHERE login = ?");
if (!$stmt) {
    echo "Erro interno no banco de dados.";
    exit;
}
$stmt->bind_param("s", $login);
if (!$stmt->execute()) {
    echo "Erro interno no banco de dados.";
    $stmt->close();
    exit;
}
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "Este login já está em uso.";
    $stmt->close();
    exit;
}
$stmt->close();

// Insere novo usuário
$stmt = $conn->prepare("INSERT INTO accounts (login, password, lastactive, accessLevel, lastIP) VALUES (?, ?, ?, ?, ?)");
if (!$stmt) {
    echo "Erro interno no banco de dados.";
    exit;
}

$lastActive = time() * 1000;  // timestamp em milissegundos
$accessLevel = 0;

$stmt->bind_param("sssis", $login, $hashedPassword, $lastActive, $accessLevel, $ip);

if ($stmt->execute()) {
    echo "OK";
} else {
    echo "Erro ao criar a conta.";
}

$stmt->close();
$conn->close();
