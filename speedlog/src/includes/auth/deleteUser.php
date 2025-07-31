<?php
session_start();
require_once '../../app/config/includes.php';
verificarAutenticacao();

header('Content-Type: application/json; charset=utf-8');

function jsonResponse(string $status, string $message): void
{
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

if ($id <= 0) {
    jsonResponse('error', 'ID de usuário inválido.');
}

// Protege para não excluir o próprio usuário logado (opcional)
if ($_SESSION['user_id'] == $id) {
    jsonResponse('error', 'Você não pode excluir a si mesmo.');
}

try {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() === 0) {
        jsonResponse('error', 'Usuário não encontrado.');
    }

    jsonResponse('success', 'Usuário excluído com sucesso.');
} catch (PDOException $e) {
    jsonResponse('error', 'Erro ao excluir usuário: ' . $e->getMessage());
}
