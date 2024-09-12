<?php
require_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["login"], $_POST["password"], $_POST["confirm_password"])) {
        $login = $_POST["login"];
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];

        // Verifique se as senhas coincidem
        if ($password === $confirm_password) {
            // Gerar hash SHA-1 da senha e codificar em Base64
            $hashedPassword = base64_encode(sha1($password, true));

            // Conectar ao banco de dados
            $conn = new mysqli($host, $user, $pass, $db);

            // Verificar a conexão
            if ($conn->connect_error) {
                die("Conexão falhou: " . $conn->connect_error);
            }

            // Preparar a declaração SQL para inserir uma nova conta
            $stmt = $conn->prepare("INSERT INTO accounts (login, password, lastactive, access_level) VALUES (?, ?, ?, ?)");
            $lastactive = time(); // Timestamp do momento da criação da conta
            $access_level = 0; // Nível de acesso inicial

            // Vincular os parâmetros
            $stmt->bind_param("ssii", $login, $hashedPassword, $lastactive, $access_level);

            // Executar a declaração
            if ($stmt->execute()) {
                echo "<script>alert('Conta registrada com sucesso!'); window.location.replace(document.referrer);</script>";
                exit();
            } else {
                echo "Erro ao registrar conta: " . $stmt->error;
            }

            // Fechar a declaração e a conexão
            $stmt->close();
            $conn->close();
        } else {
            echo "<script>alert('As senhas não conhecidem!'); window.location.replace(document.referrer);</script>";
            exit();
        }
    } else {
        echo "<script>alert('Algo deu errado, tente novamente!'); window.location.replace(document.referrer);</script>";
        exit();
    }
} else {
    echo "<script>alert('Requisiçã inválida'); window.location.replace(document.referrer);</script>";
    exit();
}
?>
