<?php
include '../../config/dbconnect.php';

// API de Dogecoin (exemplo: Block.io)
$api_key = "SUA_API_KEY";
$pin = "SEU_PIN";

// Seleciona saques pendentes
$sql = "SELECT * FROM dogecoin_requests WHERE status = 'Pendente'";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $wallet = $row['wallet_address'];
    $amount = $row['amount'];
    $id = $row['id'];

    // Faz a transação na API Block.io
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://block.io/api/v2/withdraw/?api_key=$api_key&pin=$pin&amount=$amount&to_addresses=$wallet");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = json_decode(curl_exec($ch), true);
    curl_close($ch);

    // Verifica se a transação foi bem-sucedida
    if ($response["status"] == "success") {
        // Atualiza a solicitação como processada
        $sql = "UPDATE dogecoin_requests SET status = 'Processado' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        echo "Pagamento de $amount DOGE enviado para $wallet!";
    } else {
        echo "Erro ao enviar Dogecoin para $wallet.";
    }
}
?>
