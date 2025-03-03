<?php
session_start();
include '../config/dbconnect.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['usuario_id'];
    $nome = trim($_POST['nome']);
    $sexo = $_POST['sexo'];
    $avatar = ($sexo == "Macho") ? "macho_base.png" : "femea_base.png";

    if (empty($nome) || empty($sexo)) {
        echo "Preencha todos os campos.";
        exit();
    }

    $sql = "INSERT INTO site_characters (user_id, nome, avatar_sexo, equipamentos) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $equipamentos = json_encode([]); // Começa sem nenhum equipamento
    $stmt->bind_param("isss", $user_id, $nome, $sexo, $equipamentos);
    if ($stmt->execute()) {
        header("Location: ../dashboard.php");
    } else {
        echo "Erro ao criar personagem.";
    }
    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Jogador</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/script.js" defer></script>
</head>
<body>
    <style>
        body {
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            
        }
        header {
            width: 100%;
        }
        header img {
            width: 100%;
            border-radius: 3px;
        }
        .container {

        }
    </style>
    <header>
        <img src="../assets/img/others/headerCharacterCreate.png" alt="headerBanner.png">
    </header>

    <div class="container">
        <h2>Criar Personagem</h2>
        <form action="" method="post">
            <label>Nome do Personagem:</label>
            <input type="text" name="nome" required>
            <label>Sexo:</label>
            <select name="sexo" required>
                <option value="Macho">Macho</option>
                <option value="Femea">Femea</option>
            </select>
            <button type="submit">Criar</button>
        </form>
    </div>
</body>
</html>
