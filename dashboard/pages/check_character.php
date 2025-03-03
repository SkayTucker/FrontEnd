<?php
session_start();
include '../config/dbconnect.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["status" => "error", "message" => "Usuário não logado."]);
    exit();
}

$user_id = $_SESSION['usuario_id'];
$sql = "SELECT * FROM site_characters WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$character = $result->fetch_assoc();
$stmt->close();

if ($character) {
    echo json_encode(["status" => "success", "character" => $character]);
} else {
    echo json_encode(["status" => "no_character"]);
}
?>
