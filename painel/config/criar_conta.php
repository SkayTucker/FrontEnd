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

// Obtém o user_id da sessão
$user_id = $_SESSION['usuario_id'];

// Processa o formulário de criação de conta
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = trim($_POST['login']);
    $senha = trim($_POST['senha']);
    $lastIP = $_SERVER['REMOTE_ADDR']; // Captura o IP do usuário

    // Hash da senha usando SHA-1
    $senhaHash = sha1($senha);
    
    // Codifica o hash em Base64
    $senhaBase64 = base64_encode(hex2bin($senhaHash)); // Convertendo o hash para binário antes de codificar

    // Prepara a consulta SQL para inserir a nova conta
    $sql = "INSERT INTO accounts (login, password, lastactive, accessLevel, lastIP, user_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Define os valores
    $lastActive = time(); // Timestamp atual
    $accessLevel = 0; // Nível de acesso padrão

    // Verifica se a consulta está preparada corretamente
    if ($stmt) {
        // Aqui, usamos a variável $user_id obtida da sessão
        $stmt->bind_param("ssissi", $login, $senhaBase64, $lastActive, $accessLevel, $lastIP, $user_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Conta criada com sucesso!'); window.location.href = '../dashboard.php';</script>";
        } else {
            echo "<script>alert('Erro ao criar a conta.'); window.location.href = '../dashboard.php';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Erro na consulta.'); window.location.href = '../dashboard.php';</script>";
    }
}

// Fecha a conexão
$conn->close();
?>
