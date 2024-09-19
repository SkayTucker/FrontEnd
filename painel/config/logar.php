<?php
include 'dbconnect.php'; // Inclui o arquivo de conexão

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os dados do formulário
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    
    /*Verificamos o método de requisição (POST) e
    obtemos os valores de email e senha do formulário.*/

    // Prepara a consulta SQL
    $sql = "SELECT * FROM usuarios WHERE email = ? AND senha = ?";
    
    //Utilizamos prepare() para evitar SQL Injection e executamos a consulta ao banco de dados.
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $senha);
    $stmt->execute();
    $resultado = $stmt->get_result();



    // Verifica se a consulta retornou algum resultado
    if ($resultado->num_rows > 0) {
        echo "Login bem-sucedido!";
        // Aqui você pode redirecionar para uma página de painel ou dashboard
    } else {
        echo "Usuário ou senha inválidos.";
    }

    // Fecha a conexão
    $stmt->close();
    $conn->close();
}
?>
