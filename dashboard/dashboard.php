<?php
session_start();
include '../config/includes.php';

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="dashboard.css">
</head>

<body>

    <header>
        <!-- Usuário logado -->
        <div class="userLogged">
            <p>Bem-vindo, <?php echo $_SESSION['user_id']; ?>!</p>
            <a href="dashboard/dashboard.php">Painel</a> |
            <a href="config/logout.php">Sair</a>
        </div>
    </header>

</body>

</html>