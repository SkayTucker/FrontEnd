<?php
session_start();
require_once '../../app/config/includes.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método não permitido.']);
    exit;
}

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$nivel_acesso = ($_POST['nivel_acesso'] === 'Administrador') ? 0 : 1;

// Validação de senha
if (strlen($password) < 3 || strlen($password) > 12) {
    echo json_encode(['status' => 'error', 'message' => 'A senha deve conter entre 3 e 12 caracteres.']);
    exit;
}

// Verifica duplicidade
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR name = ?");
$stmt->execute([$email, $name]);
if ($stmt->fetch()) {
    echo json_encode(['status' => 'error', 'message' => 'E-mail ou nome de usuário já estão em uso.']);
    exit;
}

// Criptografa e insere
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users (name, email, password, nivel_acesso) VALUES (?, ?, ?, ?)");
try {
    $stmt->execute([$name, $email, $passwordHash, $nivel_acesso]);
    echo json_encode(['status' => 'success', 'message' => 'Usuário cadastrado com sucesso.']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao salvar usuário: ' . $e->getMessage()]);
}
exit;
