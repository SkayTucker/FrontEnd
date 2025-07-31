<?php
session_start();
require_once '../../app/config/includes.php';

function alertAndRedirect($mensagem, $url)
{
    echo "<script>alert(" . json_encode($mensagem) . "); window.location.href = " . json_encode($url) . ";</script>";
    exit;
}

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    alertAndRedirect("Acesso inválido.", "../../modals/solicitarVeiculo.php?plate=" . urlencode($_POST['plate'] ?? ''));
}

$user_id = $_SESSION['user_id'];
$plate = $_POST['plate'] ?? '';
$latitude = $_POST['latitude'] ?? null;
$longitude = $_POST['longitude'] ?? null;
$endereco = $_POST['endereco'] ?? null;
$km_atual = floatval($_POST['km_atual']) ?? null;
$combustivel = $_POST['combustivel'] ?? null;
$observacoes = $_POST['observacoes'] ?? null;
$checklist = $_POST['checklist'] ?? [];
$tipoPasta = $_POST['tipo_pasta'] ?? 'solicitacao';

$urlRetorno = "../../../public/dashboard.php";

if (!is_numeric($latitude) || !is_numeric($longitude) || !is_numeric($km_atual)) {
    alertAndRedirect("Localização ou quilometragem inválida.", $urlRetorno);
}

if (count($checklist) < 6) {
    alertAndRedirect("Todos os itens do checklist devem ser marcados.", $urlRetorno);
}

if (!isset($_FILES['fotos'])) {
    alertAndRedirect("Nenhuma foto enviada.", $urlRetorno);
}

$fotos = $_FILES['fotos'];
if (count($fotos['name']) > 5) {
    alertAndRedirect("Você pode enviar até 5 fotos.", $urlRetorno);
}

try {
    $pdo->beginTransaction();

    // Bloqueia o veículo para edição
    $stmt = $pdo->prepare("SELECT plate, model, availability FROM vehicles WHERE plate = ? FOR UPDATE");
    $stmt->execute([$plate]);
    $veiculo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$veiculo) {
        throw new Exception("Veículo não encontrado.");
    }
    if ($veiculo['availability'] !== 'disponível') {
        throw new Exception("Veículo não está disponível.");
    }

    // Verifica se já está em uso
    $stmt = $pdo->prepare("SELECT 1 FROM vehicle_checkin WHERE vehicle_plate = ? AND status = 'em uso'");
    $stmt->execute([$plate]);
    if ($stmt->fetch()) {
        throw new Exception("Este veículo já está em uso.");
    }

    // Atualiza disponibilidade
    $stmt = $pdo->prepare("UPDATE vehicles SET availability = 'em uso' WHERE plate = ?");
    $stmt->execute([$plate]);

    // Insere checkin
    $stmt = $pdo->prepare("INSERT INTO vehicle_checkin (user_id, vehicle_plate, status) VALUES (?, ?, 'em uso')");
    $stmt->execute([$user_id, $plate]);
    $checkinId = $pdo->lastInsertId();

    // Detalhes do checkin
    $stmt = $pdo->prepare("INSERT INTO vehicle_checkin_details 
        (checkin_id, vehicle_plate, latitude, longitude, endereco, quilometragem_inicial, observacoes) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $checkinId,
        $plate,
        $latitude,
        $longitude,
        $endereco,
        $km_atual,
        $observacoes
    ]);

    // Histórico do veículo
    $stmt = $pdo->prepare("INSERT INTO vehicle_history 
        (vehicle_plate, user_id, km_final, observacoes, devolvido_em, latitude, longitude, endereco)
        VALUES (?, ?, ?, ?, NOW(), ?, ?, ?)");
    $stmt->execute([
        $plate,
        $user_id,
        $km_atual,
        $observacoes,
        $latitude,
        $longitude,
        $endereco
    ]);

    // Itens do checklist
    $stmt = $pdo->prepare("INSERT INTO vehicle_checklist_items (checkin_id, item, status) VALUES (?, ?, 1)");
    foreach ($checklist as $item) {
        $stmt->execute([$checkinId, $item]);
    }
    // Itens extras nível óleo e combustível
    $stmt->execute([$checkinId, "nível de combustível: $combustivel"]);

    // --- Upload das fotos ---
    $userFolder = 'user_' . $user_id;
    $safePlateModel = preg_replace('/[^a-zA-Z0-9_-]/', '_', $veiculo['plate'] . '_' . $veiculo['model']);
    $dateFolder = date('Y-m-d');
    $uploadDir = __DIR__ . "/../../../uploads/$safePlateModel/$userFolder/$dateFolder/$tipoPasta/";

    // Verifica/cria pasta upload
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
        throw new Exception("Falha ao criar pasta de upload.");
    }

    $horaMinuto = date('H-i');

    $stmtInsertImg = $pdo->prepare("INSERT INTO vehicle_uploads 
        (vehicle_plate, checkin_id, upload_type, image_path, uploaded_by) 
        VALUES (?, ?, ?, ?, ?)");

    for ($i = 0; $i < count($fotos['name']); $i++) {
        if ($fotos['error'][$i] !== UPLOAD_ERR_OK) {
            throw new Exception("Erro ao enviar a foto " . htmlspecialchars($fotos['name'][$i]));
        }

        $ext = strtolower(pathinfo($fotos['name'][$i], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($ext, $allowed)) {
            throw new Exception("Formato de arquivo não permitido: " . htmlspecialchars($fotos['name'][$i]));
        }

        $newName = "{$safePlateModel}_user{$user_id}_{$horaMinuto}_{$i}.{$ext}";
        $destPath = $uploadDir . $newName;

        // Verificações para debug
        if (!is_dir($uploadDir)) {
            throw new Exception("Diretório não existe: $uploadDir");
        }
        if (!is_writable($uploadDir)) {
            throw new Exception("Diretório não é gravável: $uploadDir");
        }
        if (!file_exists($fotos['tmp_name'][$i])) {
            throw new Exception("Arquivo temporário não existe: " . $fotos['tmp_name'][$i]);
        }

        if (!move_uploaded_file($fotos['tmp_name'][$i], $destPath)) {
            throw new Exception("Falha ao mover a foto: " . htmlspecialchars($fotos['name'][$i]));
        }

        $relativePath = "uploads/$safePlateModel/$userFolder/$dateFolder/$tipoPasta/$newName";
        $stmtInsertImg->execute([$plate, $checkinId, $tipoPasta, $relativePath, $user_id]);
    }

    $pdo->commit();

    alertAndRedirect("✅ Solicitação registrada com sucesso!", $urlRetorno);
} catch (Exception $e) {
    $pdo->rollBack();
    alertAndRedirect("❌ Erro ao processar a solicitação: " . $e->getMessage(), $urlRetorno);
}
