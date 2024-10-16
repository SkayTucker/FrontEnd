<?php
// Inicia a sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Conexão com o banco de dados
include '../config/dbconnect.php';

// Obtém o ID do usuário
$usuarioId = $_SESSION['usuario_id'];

// Verifica se o usuário já tem um personagem criado
$sql = "SELECT id FROM personagens WHERE usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuarioId);
$stmt->execute();
$resultado = $stmt->get_result();

// Se já existe um personagem, redireciona ou exibe uma mensagem de erro
if ($resultado->num_rows > 0) {
    echo "Você já possui um personagem criado.";
    exit();
}

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém o gênero do personagem do formulário
    $genero = $_POST['genero'];
    
    // Verifica se o gênero foi selecionado corretamente
    if ($genero == 'male' || $genero == 'female') {
        // Insere o personagem no banco de dados
        $sql = "INSERT INTO personagens (usuario_id, genero) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $usuarioId, $genero);
        if ($stmt->execute()) {
            echo "Personagem criado com sucesso!";
        } else {
            echo "Erro ao criar personagem.";
        }
        
        // Fecha a conexão
        $stmt->close();
        $conn->close();
    } else {
        echo "Por favor, escolha um gênero válido.";
    }
}
?>
