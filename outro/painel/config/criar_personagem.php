só é permitido 3 personagens por conta. 

<?php
// Inicia a sessão
session_start();

// Conexão com o banco de dados
include 'dbconnect.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Processa o formulário de criação de personagem
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accountName = $_SESSION['usuario_id'];
    $charName = trim($_POST['char_name']);
    $level = 1; // O nível sempre será 1
    $face = (int) $_POST['face'];
    $hairStyle = (int) $_POST['hair_style'];
    $hairColor = (int) $_POST['hair_color'];
    $sex = (int) $_POST['sex'];
    $race = (int) $_POST['race']; // Obtém a raça do formulário
    $classId = (int) $_POST['class_id']; // Obtém a classe do formulário

    // Prepara a consulta SQL para inserir o novo personagem
    $sql = "INSERT INTO character_site (account_name, char_name, level, face, hairStyle, hairColor, sex, race, classid) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepara a consulta
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        // Associa os parâmetros
        $stmt->bind_param("ssiisssss", 
            $accountName, $charName, $level, 
            $face, $hairStyle, $hairColor, 
            $sex, $race, $classId);
        
        // Executa a consulta
        if ($stmt->execute()) {
            echo "<script>alert('Personagem criado com sucesso!'); window.location.href = '../dashboard.php';</script>";
        } else {
            echo "<script>alert('Erro ao criar o personagem.'); window.location.href = '../dashboard.php';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Erro na consulta.'); window.location.href = '../dashboard.php';</script>";
    }
}

// Fecha a conexão
$conn->close();
?>
