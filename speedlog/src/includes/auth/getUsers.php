<?php
require '../../app/config/includes.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT id, name, email, nivel_acesso FROM users ORDER BY name ASC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "users" => $users
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Erro ao buscar usuários: " . $e->getMessage()
    ]);
}
