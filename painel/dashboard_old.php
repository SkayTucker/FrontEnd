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

// Busca as contas do usuário
$sqlAccounts = "SELECT login FROM accounts WHERE user_id = ? LIMIT 3";
$stmtAccounts = $conn->prepare($sqlAccounts);
$stmtAccounts->bind_param("i", $usuarioId);
$stmtAccounts->execute();
$resultadoAccounts = $stmtAccounts->get_result();

// Conta o número de contas
$numAccounts = $resultadoAccounts->num_rows;

// Busca os personagens do usuário
$sqlCharacters = "SELECT charId, char_name, level FROM character_site WHERE account_name = ?";
$stmtCharacters = $conn->prepare($sqlCharacters);
$stmtCharacters->bind_param("s", $usuarioId);
$stmtCharacters->execute();
$resultadoCharacters = $stmtCharacters->get_result();

// Conta o número de personagens
$numCharacters = $resultadoCharacters->num_rows;

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
    </nav>
</header>    

<main>
    <!-- Carrega os personagens se criados, caso não tenha conta criada, retorne que não existe personagem -->
    <div class="myChars">
        <div class="titulo">
            <h1>Meus Personagens</h1>
            <h5>Máx 3 Personagens</h5>
        </div>
        <ul style="flex-direction:column;">
            <?php if ($numCharacters > 0): ?>
                <?php while ($personagem = $resultadoCharacters->fetch_assoc()): ?>
                    <li>
                        ID: <?php echo htmlspecialchars($personagem['charId']); ?>, 
                        Nome: <?php echo htmlspecialchars($personagem['char_name']); ?>, 
                        Nível: <?php echo htmlspecialchars($personagem['level']); ?>
                    </li>
                <?php endwhile; ?>
            <?php else: ?>
                <li>Nenhum personagem encontrado.</li>
            <?php endif; ?>
        </ul>
    </div><br>

    <!-- Cria conta pra poder Criar personagem, se a conta estiver criada, exiba as contas -->
    <div class="Contas">
        <div class="myContas">
            <h2>Contas</h2>
            <ul>
                <?php if ($numAccounts > 0): ?>
                    <?php while ($conta = $resultadoAccounts->fetch_assoc()): ?>
                        <li><?php echo htmlspecialchars($conta['login']); ?></li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li>Nenhuma conta criada.</li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Menu de criação de contas, se já tiver 3 contas, esconda-o -->
        <?php if ($numAccounts < 3): ?>
            <div class="criarConta">
                <h2>Criar Nova Conta</h2>
                <form action="config/criar_conta.php" method="post">
                    <label for="login">Login:</label>
                    <input type="text" name="login" id="login" required>
                    <br><br>

                    <label for="senha">Senha:</label>
                    <input type="password" name="senha" id="senha" required>
                    <br><br>

                    <input type="submit" value="Criar Conta">
                </form>
            </div>
        <?php endif; ?>
    </div>


</main>

<script src="../assets/js/Acessibilidade.js"></script>
</body>
</html>
