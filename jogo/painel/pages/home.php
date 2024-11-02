<?php
// Inicia a sessão
session_start();
// Conexão com o banco de dados
include '../config/dbconnect.php';
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
}

// Fecha a conexão
$stmt->close();
$conn->close();
?>

<div class="container">
    <h1>Bem vindo a Aden!</h1>

</div>

