<?php
include '../../public/db/dbConnection.php';
$db = new DBConnection();
$conn = $db->connect();  // Conexão PDO

// Verificar se os dados do produto e validade foram enviados via POST
if (isset($_POST['product_name']) && isset($_POST['product_category']) && isset($_POST['validity_type'])) {
    $productName = $_POST['product_name'];
    $productCategory = $_POST['product_category'];
    $validityType = $_POST['validity_type'];

    // Verificar se o nome do produto e a categoria não são vazios
    if (empty($productName) || empty($productCategory)) {
        echo 'Nome do produto ou categoria não pode estar vazio.';
        exit;
    }

    // Preparar a consulta para inserir dados na tabela 'products' usando PDO
    $product_query = "INSERT INTO products (product_name, product_category) VALUES (:name, :category)";
    $product_stmt = $conn->prepare($product_query);
    $product_stmt->bindParam(':name', $productName);
    $product_stmt->bindParam(':category', $productCategory);

    // Executar a consulta
    $product_result = $product_stmt->execute();

    // Verificar se inseriu corretamente
    if ($product_result) {
        // Se a validade foi fornecida e se a data de validade está presente
        $validityDate = null;
        if ($validityType == 'manual' && isset($_POST['validity_date'])) {
            $validityDate = $_POST['validity_date'];

            if (empty($validityDate)) {
                echo 'Data de validade é obrigatória.';
                exit;
            }
        } 
        
        if ($validityType == 'classificacao' && isset($_POST['validity_days'])) {
            // Calcular a data de validade com base na classificação
            $validityDays = $_POST['validity_days'];
            $today = new DateTime();
            $validityDate = $today->add(new DateInterval('P' . $validityDays . 'D'))->format('Y-m-d');
        }
        

        if ($validityDate) {
            // Inserir validade manual ou calculada
            $validity_query = "INSERT INTO validity (product_id, expiration_date) VALUES ((SELECT LAST_INSERT_ID()), :expiration_date)";
            $validity_stmt = $conn->prepare($validity_query);
            $validity_stmt->bindParam(':expiration_date', $validityDate);

            $validity_stmt->execute();
        }

        echo 'Produto cadastrado com sucesso!';
    } else {
        echo 'Erro ao cadastrar o produto.';
    }
}
?>
