<?php
session_start();
require_once '../../app/config/includes.php';
verificarAutenticacao();

header('Content-Type: application/json; charset=utf-8');

function jsonResponse(string $status, string $message, string $redirect = ''): void
{
    echo json_encode([
        'status' => $status,
        'message' => $message,
        'redirect' => $redirect,
    ]);
    exit;
}

// Só aceita POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse('error', 'Requisição inválida.', '../../public/veiculos.php');
}

try {
    $plate = strtoupper(trim($_POST['plate'] ?? ''));
    $brand = trim($_POST['brand'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $year = (int) ($_POST['year'] ?? 0);
    $availability = strtolower(trim($_POST['availability'] ?? ''));
    $created_by = $_SESSION['user_id'] ?? null;

    // Verifica autenticação
    if (!$created_by) {
        jsonResponse('error', 'Usuário não autenticado.', '../../login.php');
    }

    // Campos obrigatórios
    if (!$plate || !$brand || !$model || !$year || !$availability) {
        jsonResponse('error', 'Preencha todos os campos obrigatórios.', '../../public/veiculos.php');
    }

    // Validação da placa (só letras, números e hífen, max 10 caracteres)
    if (!preg_match('/^[A-Z0-9\-]{1,10}$/', $plate)) {
        jsonResponse('error', 'Placa inválida.', '../../public/veiculos.php');
    }

    // Validação do ano (entre 1900 e 2025)
    if ($year < 1900 || $year > 2025) {
        jsonResponse('error', 'Ano inválido. Informe um ano entre 1900 e 2025.', '../../public/veiculos.php');
    }

    // Validação do availability (permitir só "disponível" ou "indisponível")
    $validAvailability = ['disponível', 'indisponível'];
    if (!in_array($availability, $validAvailability, true)) {
        jsonResponse('error', 'Disponibilidade inválida.', '../../public/veiculos.php');
    }

    // Verifica duplicidade da placa
    $stmt = $pdo->prepare("SELECT 1 FROM vehicles WHERE plate = ?");
    $stmt->execute([$plate]);
    if ($stmt->fetch()) {
        jsonResponse('error', 'Placa já cadastrada.', '../../public/veiculos.php');
    }

    $photos_dir = '/assets/img/';
    $imagem_nome = null;

    if (isset($_FILES['nova_imagem']) && $_FILES['nova_imagem']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['nova_imagem']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, ['jpg', 'jpeg'])) {
            jsonResponse('error', 'A imagem deve estar no formato JPG ou JPEG.', '../../public/veiculos.php');
        }

        // Nome da imagem = modelo do veículo, em minúsculas, com hífens e .jpg
        $nomeImagemModelo = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $model));
        $nomeGerado = $nomeImagemModelo . '.jpg';
        $destino = PUBLIC_PATH . $photos_dir . $nomeGerado;

        if (!move_uploaded_file($_FILES['nova_imagem']['tmp_name'], $destino)) {
            jsonResponse('error', 'Falha ao salvar a imagem enviada.', '../../public/veiculos.php');
        }

        $imagem_nome = $nomeGerado;
    } elseif (!empty($_POST['imagem_existente'])) {
        $imagem_nome = basename($_POST['imagem_existente']);
    }

    if (!$imagem_nome) {
        jsonResponse('error', 'Você deve selecionar ou enviar uma imagem.', '../../public/veiculos.php');
    }

    $sql = "INSERT INTO vehicles (plate, brand, model, year, availability, created_by, photos_dir)
        VALUES (:plate, :brand, :model, :year, :availability, :created_by, :photos_dir)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':plate' => $plate,
        ':brand' => $brand,
        ':model' => $model,
        ':year' => $year,
        ':availability' => $availability,
        ':created_by' => $created_by,
        ':photos_dir' => '/' . $imagem_nome
    ]);

    jsonResponse('success', 'Veículo cadastrado com sucesso.', '../../../public/dashboard.php');
} catch (PDOException $e) {
    jsonResponse('error', 'Erro ao salvar veículo: ' . $e->getMessage(), '../../public/veiculos.php');
}
