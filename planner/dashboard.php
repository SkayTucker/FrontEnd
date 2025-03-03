<?php
session_start();
include 'config/dbconnect.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
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
    header("Location: pages/criar_personagem.php");
    exit();
}

$avatar = "assets/img/avatars/" . (($character['avatar_sexo'] == "Macho") ? "macho_base.png" : "femea_base.png");
$equipamentos = json_decode($character['equipamentos'], true);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Jogador</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <div class="sidebar">
        <div class="avatar">
            <div class="avatar-container">
                <img src="<?php echo $avatar; ?>" class="avatar-base">
                <?php if (!empty($equipamentos)): ?>
                    <?php foreach ($equipamentos as $equipamento): ?>
                        <img src="assets/imgs/items/<?php echo $equipamento; ?>" class="avatar-item">
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <p><?php echo htmlspecialchars($character['nome']); ?></p>
        <p>Classe: <?php echo htmlspecialchars($character['avatar_sexo']); ?></p>
        <p>Nível: <?php echo $character['nivel']; ?></p>
        <p>Ouro: <?php echo $character['gold']; ?></p>

        <ul>
            <li><a href="#" onclick="carregarPagina('inventario')">Inventário</a></li>
            <li><a href="#" onclick="carregarPagina('arena')">Arena</a></li>
            <li><a href="#" onclick="carregarPagina('ranking')">Ranking</a></li>
            <li><a href="logout.php">Sair</a></li>
        </ul>
    </div>

</body>
</html>
