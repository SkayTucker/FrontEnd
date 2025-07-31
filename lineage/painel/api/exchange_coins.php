<?php

$user_id = $_SESSION['user_id']; // `login` da conta
$item_id = 4356; // ID das coins in-game
$exchange_rate = 10; // Taxa de conversão (10 coins in-game = 1 coin no site)

// Verifica quantas coins in-game o jogador tem
$sql = "SELECT SUM(items.count) AS total_coins FROM items 
        INNER JOIN characters ON items.owner_id = characters.charId
        WHERE characters.account_name = ? AND items.item_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $user_id, $item_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$coins_in_game = $row['total_coins'] ?? 0;

// Verifica se tem coins suficientes para trocar
if ($coins_in_game >= $exchange_rate) {
    $coins_to_add = floor($coins_in_game / $exchange_rate);
    $coins_to_remove = $coins_to_add * $exchange_rate;

    // Remove as coins in-game
    $sql = "UPDATE items 
            INNER JOIN characters ON items.owner_id = characters.charId
            SET items.count = items.count - ?
            WHERE characters.account_name = ? AND items.item_id = ? AND items.count >= ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isii", $coins_to_remove, $user_id, $item_id, $coins_to_remove);
    $stmt->execute();

    // Adiciona coins ao site
    $sql = "INSERT INTO site_coins (login, coins) VALUES (?, ?) 
            ON DUPLICATE KEY UPDATE coins = coins + VALUES(coins)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $user_id, $coins_to_add);
    $stmt->execute();

    echo "Conversão realizada com sucesso! Você ganhou $coins_to_add coins no site.";
} else {
    echo "Você precisa de pelo menos $exchange_rate coins in-game para trocar!";
}
?>
