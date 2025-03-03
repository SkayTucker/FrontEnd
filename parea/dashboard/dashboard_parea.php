<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

include '../public/db/dbConnection.php';

$db = new DBConnection();
$conn = $db->connect();

// Obtendo produtos cadastrados
$filter = $_GET['filter'] ?? '';
$query = "
    SELECT p.*, v.expiration_date
    FROM products p
    LEFT JOIN validity v ON p.id = v.product_id
    WHERE p.product_name LIKE :filter OR p.product_category LIKE :filter
    ORDER BY p.created_at DESC";

$stmt = $conn->prepare($query);
$filterParam = "%$filter%";
$stmt->bindParam(':filter', $filterParam);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        .validity-expired {
            background-color: #ff4d4d;
            color: white;
        }

        /* Vermelho */
        .validity-warning {
            background-color: #ffae42;
            color: black;
        }

        /* Laranja */
        .validity-alert {
            background-color: #fff176;
            color: black;
        }

        /* Amarelo */
        .validity-ok {
            background-color: #a5d6a7;
            color: black;
        }

        /* Estilos para modais */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <nav>
        <ul>
            <li><a href="#">Outro</a></li>
            <li><a href="logout.php">Sair</a></li>
        </ul>
    </nav>

    <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h1>

    <!-- Botão para abrir modal de cadastro -->
    <button id="openModalBtn">Cadastrar Produto</button>
    <!-- Modal de cadastro de produto -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Cadastro de Produto</h2>
            <form id="productForm" method="POST" action="db/productQueries.php">
                <div id="step1" class="step">
                    <h3>Etapa 1: Informações Básicas</h3>
                    <label for="product_name">Nome do Produto:</label>
                    <input type="text" id="product_name" name="product_name" required><br><br>

                    <label for="product_category">Categoria:</label>
                    <select id="product_category" name="product_category" required>
                        <option value="Limpeza">Limpeza</option>
                        <option value="Congelado">Congelado</option>
                        <option value="Enlatado">Enlatado</option>
                        <option value="Perecível">Perecível</option>
                    </select><br><br>

                    <button type="button" onclick="nextStep(2)">Próxima Etapa</button>
                </div>

                <div id="step2" class="step" style="display:none;">
                    <h3>Etapa 2: Validade</h3>

                    <div id="validity_section">
                        <label for="validity_type">Tipo de Validade:</label>
                        <select id="validity_type" name="validity_type" onchange="toggleValidityDate()">
                            <option value="classificacao">Classificação (automática)</option>
                            <option value="manual">Escolher data</option>
                        </select><br><br>

                        <div id="validity_classification" style="display:none;">
                            <label for="validity_days">Classificação:</label>
                            <select id="validity_days" name="validity_days">
                                <option value="1">Vence amanhã</option>
                                <option value="2">Vence em 2 dias</option>
                                <option value="5">Vence em 5 dias</option>
                                <option value="10">Vence em 10 dias</option>
                                <option value="15">Vence em 15 dias</option>
                            </select><br><br>
                        </div>

                        <div id="validity_manual" style="display:none;">
                            <label for="validity_date">Data de Validade:</label>
                            <input type="date" id="validity_date" name="validity_date"><br><br>

                        </div>
                    </div>

                    <button type="button" onclick="prevStep(1)">Voltar</button>
                    <button type="submit">Cadastrar Produto</button>
                </div>
            </form>

        </div>
    </div>

    <hr>

    <section id="lista-produtos">
        <h2>Produtos Cadastrados</h2>

        <form method="GET">
            <input type="text" name="filter" placeholder="Filtrar por nome ou categoria"
                value="<?php echo htmlspecialchars($filter); ?>">
            <button type="submit">Filtrar</button>
        </form>

        <?php if (count($products) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Categoria</th>
                        <th>Data de Cadastro</th>
                        <th>Validade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($products as $product):
                        $validity_text = "Sem validade";  // Default text caso não haja validade
                
                        // Verifica se há uma validade associada ao produto
                        if (isset($product['expiration_date']) && !empty($product['expiration_date'])) {
                            $validity_text = "Válido até " . date('d/m/Y', strtotime($product['expiration_date']));
                        }
                        ?>
                        <tr>
                            <td><?php echo $product['id']; ?></td>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($product['product_category']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($product['created_at'])); ?></td>
                            <td><?php echo $validity_text; ?></td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum produto cadastrado.</p>
        <?php endif; ?>
    </section>

    <script>
        // Função para abrir o modal
        document.getElementById("openModalBtn").onclick = function () {
            document.getElementById("productModal").style.display = "block";
        }

        // Função para fechar o modal
        document.querySelector(".close").onclick = function () {
            document.getElementById("productModal").style.display = "none";
        }
        // Função para passar para a próxima etapa do formulário
        function nextStep(step) {
            document.getElementById('step' + (step - 1)).style.display = 'none';
            document.getElementById('step' + step).style.display = 'block';
        }

        // Função para voltar para a etapa anterior do formulário
        function prevStep(step) {
            document.getElementById('step' + (step + 1)).style.display = 'none';
            document.getElementById('step' + step).style.display = 'block';
        }


        // Validar cada etapa do cadastro
        function validateStep(step) {
            if (step == 1) {
                var productName = document.getElementById("product_name").value;
                var productCategory = document.getElementById("product_category").value;
                if (!productName || !productCategory) {
                    alert("Nome e Categoria do produto são obrigatórios!");
                    return false;
                }
            }
            if (step == 2) {
                var validityType = document.getElementById("validity_type").value;
                if (validityType == "manual" && !document.getElementById("validity_date").value) {
                    alert("Por favor, escolha uma data de validade.");
                    return false;
                }
            }
            return true;
        }

        // Submeter o formulário
        function submitProductForm() {
            document.getElementById("productForm").submit();
        }

        function toggleValidityDate() {
            var validityType = document.getElementById('validity_type').value;
            if (validityType === 'manual') {
                document.getElementById('validity_manual').style.display = 'block';
                document.getElementById('validity_classification').style.display = 'none';
            } else {
                document.getElementById('validity_manual').style.display = 'none';
                document.getElementById('validity_classification').style.display = 'block';
            }
        }


        // Inicializar a exibição correta ao carregar a página
        toggleValidityDate();

    </script>


</body>

</html>