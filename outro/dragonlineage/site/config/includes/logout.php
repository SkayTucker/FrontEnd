<?php
// Inicia a sessão (se ainda não estiver iniciada)
session_start();

// Remove todas as variáveis de sessão
session_unset();

// Destrói a sessão
session_destroy();

// Redireciona para a página de login ou outra página desejada após o logout
header("Location: ../../index.php");
exit();
?>
