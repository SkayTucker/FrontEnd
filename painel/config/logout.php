<?php
session_start(); // Inicia a sessão

// Limpa todas as variáveis de sessão
session_unset();

// Destrói a sessão atual
session_destroy();

// Redireciona o usuário para a página de login
header("Location: ../login.php");
exit();
?>
