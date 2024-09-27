<?php 

$hostname = "localhost";
$banco = "dragonhb";
$user = "root";
$senha = "1234";

// Criando a conexão
$conn = new mysqli($hostname, $user, $senha, $banco);

// Verificando a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
