<?php
session_start();
require_once '../../app/config/includes.php';

function alertAndRedirect($msg, $url)
{
    echo "<script>alert(" . json_encode($msg) . "); window.location.href = " . json_encode($url) . ";</script>";
    exit;
}

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    alertAndRedirect("Acesso inválido.", "../../modals/devolverVeiculo.php?plate=" . urlencode($_POST['plate'] ?? ''));
}

$user_id = $_SESSION['user_id'];
$plate = $_POST['plate'] ?? '';
$checkinId = $_POST['checkin_id'] ?? null;
$latitude = $_POST['latitude'] ?? null;
$longitude = $_POST['longitude'] ?? null;
$endereco = $_POST['endereco'] ?? null;
$km_final = isset($_POST['quilometragem_final']) ? floatval($_POST['quilometragem_final']) : null;
$nivel_oleo = $_POST['nivel_oleo'] ?? null;
$combustivel = $_POST['combustivel'] ?? null;
$observacoes = $_POST['observacoes'] ?? null;
$checklist = $_POST['checklist'] ?? [];
$tipoPasta = $_POST['tipo_pasta'] ?? 'devolucao';

$urlRetorno = "../../../public/dashboard.php";

// Validações básicas
if (!is_numeric($latitude)) {
    alertAndRedirect("Latitude inválida.", $urlRetorno);
}
if (!is_numeric($longitude)) {
    alertAndRedirect("Longitude inválida.", $urlRetorno);
}
if (!is_numeric($km_final)) {
    alertAndRedirect("Quilometragem final inválida.", $urlRetorno);
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

// Limita a placa para o tamanho máximo esperado no banco (exemplo 10 caracteres)
$plate = substr($plate, 0, 10);

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("
        SELECT d.quilometragem_inicial, v.brand, v.model, v.plate
        FROM vehicle_checkin c
        JOIN vehicle_checkin_details d ON c.id = d.checkin_id
        JOIN vehicles v ON v.plate = c.vehicle_plate
        WHERE c.id = ? AND c.user_id = ? AND c.status = 'em uso'
        FOR UPDATE
    ");
    $stmt->execute([$checkinId, $user_id]);
    $checkin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$checkin) {
        throw new Exception("Nenhum check-in em uso encontrado.");
    }

    if ($km_final < $checkin['quilometragem_inicial']) {
        throw new Exception("Quilometragem final não pode ser menor que a inicial.");
    }

    // Use a placa do banco para garantir consistência
    $plateBanco = substr($checkin['plate'], 0, 10);

    $stmt = $pdo->prepare("UPDATE vehicles SET availability = 'disponível' WHERE plate = ?");
    $stmt->execute([$plateBanco]);

    $stmt = $pdo->prepare("UPDATE vehicle_checkin SET status = 'finalizado' WHERE id = ?");
    $stmt->execute([$checkinId]);

    $stmt = $pdo->prepare("
        UPDATE vehicle_checkin_details
        SET quilometragem_final = ?, latitude = ?, longitude = ?, endereco = ?, observacoes = ?, devolvido_em = NOW()
        WHERE checkin_id = ?
    ");
    $stmt->execute([$km_final, $latitude, $longitude, $endereco, $observacoes, $checkinId]);

    $stmt = $pdo->prepare("
        INSERT INTO vehicle_history 
        (vehicle_plate, user_id, km_final, observacoes, devolvido_em, latitude, longitude, endereco)
        VALUES (?, ?, ?, ?, NOW(), ?, ?, ?)
    ");
    $stmt->execute([$plateBanco, $user_id, $km_final, $observacoes, $latitude, $longitude, $endereco]);

    $stmt = $pdo->prepare("DELETE FROM vehicle_checklist_items WHERE checkin_id = ?");
    $stmt->execute([$checkinId]);

    $stmt = $pdo->prepare("INSERT INTO vehicle_checklist_items (checkin_id, item, status) VALUES (?, ?, 1)");
    foreach ($checklist as $item) {
        $stmt->execute([$checkinId, $item]);
    }
    if ($nivel_oleo !== null) {
        $stmt->execute([$checkinId, "nível de óleo: $nivel_oleo"]);
    }
    if ($combustivel !== null) {
        $stmt->execute([$checkinId, "nível de combustível: $combustivel"]);
    }

    $userFolder = 'user_' . $user_id;
    $safePlateModel = preg_replace('/[^a-zA-Z0-9_-]/', '_', $plateBanco . '_' . $checkin['model']);
    $dateFolder = date('Y-m-d');
    $uploadDir = __DIR__ . "/../../../uploads/$safePlateModel/$userFolder/$dateFolder/$tipoPasta/";

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

        if (!move_uploaded_file($fotos['tmp_name'][$i], $destPath)) {
            throw new Exception("Falha ao mover a foto: " . htmlspecialchars($fotos['name'][$i]));
        }

        $relativePath = "uploads/$safePlateModel/$userFolder/$dateFolder/$tipoPasta/$newName";
        $stmtInsertImg->execute([$plateBanco, $checkinId, $tipoPasta, $relativePath, $user_id]);
    }

    $pdo->commit();

    error_log("Devolução - user_id: $user_id, plate: $plateBanco, checkin_id: $checkinId, km_final: $km_final");

    alertAndRedirect("✅ Veículo devolvido com sucesso!", $urlRetorno);

} catch (Exception $e) {
    $pdo->rollBack();
    alertAndRedirect("❌ Erro ao processar a devolução: " . $e->getMessage(), $urlRetorno);
}
