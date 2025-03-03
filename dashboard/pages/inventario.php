<?php
session_start();
include '../config/dbconnect.php';

if (!isset($_SESSION['usuario_id'])) {
    echo "Acesso negado.";
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

if (!$character) {
    echo "Você precisa criar um personagem primeiro.";
    exit();
}
?>

<h2>Inventário de <?php echo htmlspecialchars($character['nome']); ?></h2>
<p>O inventário será implementado aqui...</p>
