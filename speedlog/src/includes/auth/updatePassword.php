<?php
session_start();
require_once '../../app/config/includes.php'; // Ajuste esse caminho conforme sua estrutura
verificarAutenticacao();

header('Content-Type: application/json; charset=utf-8');

function jsonResponse(string $status, string $message): void {
    echo json_encode([
        'status' => $status,
        'message' => $message,
    ]);
    exit;
}

// Verifica autenticação
if (!isset($_SESSION['user_id'])) {
    jsonResponse('error', 'Usuário não autenticado.');
}

// Só aceita POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse('error', 'Requisição inválida.');
}

$id = intval($_POST['id'] ?? 0);
$password = trim($_POST['password'] ?? '');

if ($id <= 0 || strlen($password) < 3 || strlen($password) > 12) {
    jsonResponse('error', 'ID inválido ou senha fora do padrão (3 a 12 caracteres).');
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$passwordHash, $id]);

    if ($stmt->rowCount() === 0) {
        jsonResponse('error', 'Usuário não encontrado ou senha igual à atual.');
    }

    jsonResponse('success', 'Senha atualizada com sucesso.');
} catch (PDOException $e) {
    jsonResponse('error', 'Erro ao atualizar senha: ' . $e->getMessage());
}
