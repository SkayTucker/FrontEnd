<?php
require_once '../../app/config/includes.php';
verificarAutenticacao();

$user_id = $_SESSION['user_id'] ?? null;
$plate = $_POST['plate'] ?? null;
$motivo = $_POST['motivo'] ?? null;
$destino = $_POST['destino'] ?? null;

if (!$user_id || !$plate || !$motivo || !$destino) {
    http_response_code(400);
    echo "Dados incompletos.";
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO veiculos_solicitados (user_id, plate, motivo, destino)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$user_id, $plate, $motivo, $destino]);

    echo "Solicitação enviada com sucesso!";
} catch (PDOException $e) {
    error_log("Erro ao solicitar veículo: " . $e->getMessage());
    echo "Erro ao solicitar veículo.";
}
