<?php
class DBConnection {
    private $pdo;
    private $config;

    public function __construct() {
        $this->config = include 'dbConfig.php';
    }

    public function connect() {
        try {
            if ($this->pdo === null) {
                $this->pdo = new PDO(
                    "mysql:host={$this->config['host']};dbname={$this->config['dbname']}",
                    $this->config['user'],
                    $this->config['password']
                );
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            return $this->pdo;
        } catch (PDOException $e) {
            if (getenv('APP_ENV') === 'development') {
                die("Erro na conexão com o banco de dados: " . $e->getMessage());
            }
            die("Erro na conexão com o banco de dados.");
        }
    }

    public function disconnect() {
        $this->pdo = null;
    }
}
?>
