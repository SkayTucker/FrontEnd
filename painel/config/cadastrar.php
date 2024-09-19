<?php
include 'conexao.php'; // Inclui o arquivo de conexão

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    
    // Verifica se o email já está cadastrado
    $sql_verificar = "SELECT * FROM usuarios WHERE email = ?";

    //Utilizamos prepare() para evitar SQL Injection e executamos a consulta ao banco de dados.
    $stmt_verificar = $conn->prepare($sql_verificar);
    $stmt_verificar->bind_param("s", $email);
    $stmt_verificar->execute();
    
    $resultado_verificar = $stmt_verificar->get_result();
    
    if ($resultado_verificar->num_rows > 0) {
        echo "O email já está cadastrado.";
    } else {
        // Prepara a consulta SQL para inserir um novo usuário
        $sql_inserir = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";

        //Utilizamos prepare() para evitar SQL Injection e executamos a consulta ao banco de dados.
        $stmt_inserir = $conn->prepare($sql_inserir);
        $stmt_inserir->bind_param("sss", $nome, $email, $senha);
        
        if ($stmt_inserir->execute()) {
            echo "Usuário cadastrado com sucesso!";
        } else {
            echo "Erro ao cadastrar o usuário: " . $conn->error;
        }
        
        // Fecha a conexão
        $stmt_inserir->close();
    }
    
    $stmt_verificar->close();
    $conn->close();
}
?>
