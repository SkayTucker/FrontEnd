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
    <title>Dashboard</title>
</head>
<body>

<header class="gradient-dourado">
        <div id="logo">
            <img src="../assets/img/logo.png" alt="" width="48">
        </div>    
    <nav class="navDashboard">
        <ul>
            <li>Nome do Usuário: <?php echo htmlspecialchars($nomeUsuario); ?></li>
            <li>Email do Usuário: <?php echo htmlspecialchars($emailUsuario); ?></li>
            <li><p><a href="config/logout.php">Logout</a></p></li>            
        </ul>    
    </nav>
</header>    


<h1>Criar Personagem</h1>
    <form action="includes/criar_personagem.php" method="POST">
        <label for="genero">Escolha o gênero do personagem:</label><br>
        <input type="radio" id="male" name="genero" value="male">
        <label for="male">Masculino</label><br>
        <input type="radio" id="female" name="genero" value="female">
        <label for="female">Feminino</label><br><br>
        <input type="submit" value="Criar Personagem">
    </form>


<main class="main-page" id="game-content">   
</main>

<footer class="gradient-dourado">
    DragonDev Desenvolvimento Web
</footer>




<script>
    // Função para carregar o conteúdo do jogo de forma dinâmica
    function carregarConteudoJogo() {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "pages/home.php", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById("game-content").innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }

    // Carrega o conteúdo do jogo ao carregar a página
    window.onload = carregarConteudoJogo;

</script>

</body>
</html>
