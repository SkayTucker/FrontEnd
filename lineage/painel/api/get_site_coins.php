<?php
$user_id = $_SESSION['user_id']; // `login` da conta

// Busca a quantidade de coins no site para esta conta
$sql = "SELECT coins FROM site_coins WHERE login = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$coins = $row['coins'] ?? 0; // Se não houver registro, retorna 0

echo $coins;
?>
