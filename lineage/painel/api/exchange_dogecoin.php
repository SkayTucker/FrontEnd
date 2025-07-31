<?php
session_start();
include '../../config/dbconnect.php';

$user_id = $_SESSION['user_id']; // Usuário logado
$exchange_rate = 1000; // Exemplo: 1000 SiteCoins = 1 Dogecoin

// Verifica se a carteira do jogador foi enviada
if (!isset($_POST['wallet_address']) || empty($_POST['wallet_address'])) {
    echo "Erro: Insira um endereço de carteira Dogecoin.";
    exit();
}

$wallet_address = $_POST['wallet_address'];

// Verifica quantos SiteCoins o jogador tem
$sql = "SELECT coins FROM site_coins WHERE login = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$site_coins = $row['coins'] ?? 0;

// Verifica se o jogador tem SiteCoins suficientes
if ($site_coins < $exchange_rate) {
    echo "Erro: Você precisa de pelo menos $exchange_rate SiteCoins para trocar por 1 Dogecoin.";
    exit();
}

// Calcula o valor em Dogecoin
$dogecoin_to_send = floor($site_coins / $exchange_rate);
$site_coins_to_remove = $dogecoin_to_send * $exchange_rate;

// Remove SiteCoins do jogador
$sql = "UPDATE site_coins SET coins = coins - ? WHERE login = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $site_coins_to_remove, $user_id);
$stmt->execute();

// Registra a solicitação na tabela `dogecoin_requests`
$sql = "INSERT INTO dogecoin_requests (login, amount, wallet_address, status) VALUES (?, ?, ?, 'Pendente')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sds", $user_id, $dogecoin_to_send, $wallet_address);
$stmt->execute();

echo "Pedido de saque de $dogecoin_to_send DOGE enviado! Será processado em breve.";
?>
