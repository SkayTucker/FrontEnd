<?php
## Dados de conexão com o servidor
ini_set('display_errors', 1);

$host = 'localhost';
$pass = '1234';
#$host = '209.14.131.214';
#$pass = 'L2cancel00!';
$user = 'root';

$db = 'dragon';
$mysqlport = 3306;

// Conexão com o banco de dados
$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);

// Defina para lançar exceções em caso de erro
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


## TOPS PVP / PK
$limit = 5;
## GMT
$gmt = 0;

// Conexão com o servidor do jogo usando fsockopen
$gameserver = @fsockopen("209.14.131.214", 2106, $errno2, $errstr2, 1);

?>
