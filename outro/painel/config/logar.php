<?php
session_start(); // Inicia a sessão
include 'dbconnect.php'; // Inclui o arquivo de conexão

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os dados do formulário e sanitiza as entradas
    $nome = filter_var(trim($_POST['nome']), FILTER_SANITIZE_STRING); // Sanitiza o nome
    $senha = trim($_POST['senha']); // Obtém a senha

    // Prepara a consulta SQL para buscar o usuário pelo nome
    $sql = "SELECT * FROM site_users WHERE nome = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nome);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Verifica se o usuário existe no banco de dados
    if ($resultado->num_rows > 0) {
        // Pega o primeiro resultado
        $usuario = $resultado->fetch_assoc();
        
        // Verifica se a senha está correta
        if (password_verify($senha, $usuario['senha'])) {
            // Senha correta: Login bem-sucedido
            
            // Armazena informações do usuário na sessão
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];

            // Redireciona o usuário para o dashboard
            header("Location: ../dashboard.php");
            exit(); // Garante que o script pare após o redirecionamento
        } else {
            // Senha incorreta
            echo "<script>alert('Usuário ou senha inválidos.'); window.location.href = 'login.php';</script>";
        }
    } else {
        // Usuário não encontrado
        echo "<script>alert('Usuário ou senha inválidos.'); window.location.href = 'login.php';</script>";
    }

    // Fecha a conexão
    $stmt->close();
    $conn->close();
}
?>
