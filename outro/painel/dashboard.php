<?php
// Inicia a sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Conexão com o banco de dados
include 'config/dbconnect.php';

// Obtendo informações do usuário
$usuarioId = $_SESSION['usuario_id'];
$sql = "SELECT nome, email, criado_em FROM site_users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuarioId);
$stmt->execute();
$resultado = $stmt->get_result();

// Verifica se o usuário foi encontrado
if ($resultado->num_rows > 0) {
    $usuario = $resultado->fetch_assoc();
    $nomeUsuario = $usuario['nome'];
    $emailUsuario = $usuario['email'];
    $ultimoAcesso = $usuario['criado_em'];
} else {
    header("Location: login.php");
    exit();
}

// Fecha a conexão
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/acessibilidade.css">
    <title>Dashboard</title>
</head>
<body>

<header class="gradient-dourado">
    <nav>
        <ul>
            <li>Nome do Usuário: <?php echo htmlspecialchars($nomeUsuario); ?></li>
            <li>Email do Usuário: <?php echo htmlspecialchars($emailUsuario); ?></li>
        </ul>

        <ul>
            <li><p><a href="config/logout.php">Logout</a></p></li>
        </ul>
        <!-- Menu Acessibilidade -->
        <ul>
            <li class="zoomInBtn" onclick="zoomIn()"></li>
            <li class="zoomOutBtn" onclick="zoomOut()"></li>
            <li id="DarkBtn" onclick="darkMode()"></li>
        </ul>            
    </nav>
</header>    

<main>
    <ul>
        <li><a href="#">Projetos</a></li>
    </ul>
</main>

<script src="../assets/js/Acessibilidade.js"></script>
</body>
</html>
