<?php 

$hostname = "localhost";
$banco = "source";
$user = "root";
$senha = "1234";

// Criando a conexão
$conn = new mysqli($hostname, $user, $senha, $banco);

// Verificando a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$loginserver = @fsockopen($hostname, 2106, $errno, $errstr, 1);

$gameserver = @fsockopen($hostname, 7777, $errno2, $errstr2, 1);
?>
