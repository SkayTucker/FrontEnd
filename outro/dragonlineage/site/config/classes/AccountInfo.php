<?php
class AccountInfo {
    private $login;
    private $password;
    private $accessLevel;
    private $lastServer;

    public function __construct($login, $password, $accessLevel, $lastServer) {
        $this->login = $login;
        $this->password = $password;
        $this->accessLevel = $accessLevel;
        $this->lastServer = $lastServer;
    }

    public function checkPassHash($password) {
        // Aplica SHA-1 e codifica em Base64
        $hashedPassword = base64_encode(sha1($password, true));

        // Compara as senhas
        return ($hashedPassword === $this->password);
    }
}

// Exemplo de uso:
$login = "nome_de_usuario";
$password = "senha_a_ser_verificada";

// Recupere os detalhes da conta do banco de dados (você precisará implementar isso)
// Aqui estou usando valores de exemplo. Substitua isso pela lógica real do banco de dados.
$infoFromDatabase = new AccountInfo("nome_de_usuario", "base64_encoded_hashed_password", 0, 1);

// Verifica a senha
if ($infoFromDatabase->checkPassHash($password)) {
    echo "Senha válida. Logue o usuário.";
} else {
    echo "Senha inválida.";
}
?>
