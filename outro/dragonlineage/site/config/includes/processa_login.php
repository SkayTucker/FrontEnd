<?php
require_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["login"], $_POST["password"])) {
        $login = $_POST["login"];
        $password = $_POST["password"];

        // Gerar hash SHA-1 da senha e codificar em Base64
        $hashedPassword = base64_encode(sha1($password, true));

        // Conectar ao banco de dados
        $conn = new mysqli($host, $user, $pass, $db);

        // Verificar a conexão
        if ($conn->connect_error) {
            die("Conexão falhou: " . $conn->connect_error);
        }

        // Preparar a declaração SQL para buscar a conta
        $stmt = $conn->prepare("SELECT login, password, access_level, lastServer FROM accounts WHERE login = ?");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $stmt->store_result();

        // Verificar se a conta existe
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($dbLogin, $dbPassword, $accessLevel, $lastServer);
            $stmt->fetch();

            // Verificar se a senha fornecida coincide com a senha armazenada
            if ($hashedPassword === $dbPassword) {
                // Senha correta, iniciar a sessão e redirecionar para a página do usuário
                session_start();
                $_SESSION['user'] = [
                    'login' => $dbLogin,
                    'access_level' => $accessLevel,
                    'last_server' => $lastServer
                ];

                header("Location: ../../index.php"); // Redirecione para a página principal
                exit();
            } else {
                echo "Senha inválida.";
            }
        } else {
            echo "Conta não encontrada.";
        }

        // Fechar a declaração e a conexão
        $stmt->close();
        $conn->close();
    } else {
        echo "Campos necessários não estão definidos.";
    }
} else {
    echo "Requisição inválida.";
}
?>
