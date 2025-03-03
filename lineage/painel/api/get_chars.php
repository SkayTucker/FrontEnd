<?php
include '../../config/includes.php';

$user_id = $_SESSION['user_id'];

// Busca os personagens do usuário
$sql = "SELECT char_name,level,curHp,curCp,curMp,base_class,classid FROM characters WHERE account_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$personagens = [];
while ($row = $result->fetch_assoc()) {
    $personagens[] = $row;
}

$stmt->close();
?>