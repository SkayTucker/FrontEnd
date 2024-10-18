<?php
// Inicia a sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    echo "Por favor, faça login para acessar o conteúdo.";
    exit();
}

// Conexão com o banco de dados
include '../config/dbconnect.php';

// Obtém o ID do usuário
$usuarioId = $_SESSION['usuario_id'];

// Verifica se o usuário já tem um personagem criado
$sql = "SELECT genero FROM personagens WHERE usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuarioId);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    // O usuário já possui um personagem
    $personagem = $resultado->fetch_assoc();
    $genero = $personagem['genero'];
    
    // Exibe uma mensagem de boas-vindas e o gênero do personagem
    echo "<h2>Bem-vindo a Aden!</h2>";
    echo "<p>Gênero: " . htmlspecialchars($genero) . "</p>";
    
    // Aqui você pode adicionar o conteúdo do jogo, como fases, missões, etc.
    echo "<p>Conheça o nosso Mundo!</p>";
} else {
    // O usuário ainda não criou um personagem
    echo "<h2>Você ainda não criou um personagem.</h2>";
    echo "<p><a href='../criar_personagem.php'>Clique aqui para criar seu personagem</a></p>";
}

$stmt->close();
$conn->close();
?>

