<?php
include 'dbConnection.php';
session_start();

$db = new DBConnection();
$conn = $db->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;

    switch ($action) {
        case 'register':
            $username = trim($_POST['username']);
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            try {
                $query = "INSERT INTO users (username, password) VALUES (:username, :password)";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':password', $password);
                $stmt->execute();
                $_SESSION['user'] = $username;
                header("Location: ../../dashboard/dashboard_parea.php");
                exit;
            } catch (PDOException $e) {
                echo "Erro ao cadastrar usuário: " . $e->getMessage();
            }
            break;

        case 'login':
            $username = trim($_POST['username']);
            $password = $_POST['password'];

            try {
                $query = "SELECT * FROM users WHERE username = :username";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':username', $username);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user'] = $username;
                    header("Location: ../../dashboard/dashboard_parea.php");
                    exit;
                } else {
                    echo "Usuário ou senha incorretos.";
                }
            } catch (PDOException $e) {
                echo "Erro ao fazer login: " . $e->getMessage();
            }
            break;

        default:
            echo "Ação inválida.";
            break;
    }
}
?>
