<?php
include 'dbconnect.php'; // Inclui o arquivo de conexão

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Gera um hash seguro para a senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    
    // Verifica se o email já está cadastrado
    $sql_verificar = "SELECT * FROM site_users WHERE email = ?";
    $stmt_verificar = $conn->prepare($sql_verificar);

    // Verifique se a preparação da consulta foi bem-sucedida
    if ($stmt_verificar) {
        $stmt_verificar->bind_param("s", $email);
        $stmt_verificar->execute();
        $resultado_verificar = $stmt_verificar->get_result();
        
        if ($resultado_verificar->num_rows > 0) {
            // Se o email já estiver cadastrado, exibe um alerta
            echo "<script>alert('O email já está cadastrado.'); window.location.href = 'login.php';</script>";
        } else {
            // Prepara a consulta SQL para inserir um novo usuário
            $sql_inserir = "INSERT INTO site_users (nome, email, senha) VALUES (?, ?, ?)";
            $stmt_inserir = $conn->prepare($sql_inserir);

            if ($stmt_inserir) {
                $stmt_inserir->bind_param("sss", $nome, $email, $senha_hash);
                
                if ($stmt_inserir->execute()) {
                    // Exibe uma mensagem de sucesso e redireciona para a página de login
                    echo "<script>alert('Usuário cadastrado com sucesso!'); window.location.href = 'login.php';</script>";
                } else {
                    // Exibe uma mensagem de erro se ocorrer falha na inserção
                    echo "<script>alert('Erro ao cadastrar o usuário.'); window.location.href = 'login.php';</script>";
                }

                $stmt_inserir->close();
            } else {
                // Se não conseguir preparar a query de inserção
                echo "<script>alert('Erro na preparação da consulta de inserção.'); window.location.href = 'login.php';</script>";
            }
        }

        $stmt_verificar->close();
    } else {
        // Se não conseguir preparar a query de verificação
        echo "<script>alert('Erro na preparação da consulta de verificação.'); window.location.href = 'login.php';</script>";
    }
    
    // Fecha a conexão
    $conn->close();
}
?>
