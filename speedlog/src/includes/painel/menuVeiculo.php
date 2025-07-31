<?php
// menuVeiculo.php

require_once '../app/config/includes.php';
verificarAutenticacao();

if (empty($_GET['plate'])) {
    echo '<p class="error-message">Placa do veículo não fornecida.</p>';
    return;
}

$plate = $_GET['plate'];
$usuarioLogadoId = $_SESSION['usuario_id'] ?? null;

function carregarVeiculo(PDO $pdo, string $plate): ?array
{
    try {
        $stmt = $pdo->prepare("SELECT * FROM vehicles WHERE plate = ?");
        $stmt->execute([$plate]);
        $veiculo = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        if ($veiculo) {
            // Tenta buscar o último check-in ativo ou mais recente
            $checkin = carregarUltimoCheckin($pdo, $plate);
            if ($checkin) {
                if (isset($checkin['mileage'])) {
                    $veiculo['mileage'] = $checkin['mileage'];
                }
                if (isset($checkin['fuel_level'])) {
                    $veiculo['fuel_level'] = $checkin['fuel_level'];
                }
                if (isset($checkin['created_at'])) {
                    $veiculo['checked_at'] = $checkin['created_at'];
                }
                if (isset($checkin['usuario'])) {
                    $veiculo['responsavel_nome'] = $checkin['usuario'];
                }
            }
        }

        return $veiculo;
    } catch (PDOException $e) {
        error_log("Erro ao carregar veículo: " . $e->getMessage());
        return null;
    }
}

function carregarUltimoCheckin(PDO $pdo, string $plate): ?array
{
    try {
        $stmt = $pdo->prepare("
            SELECT vc.*, u.name AS usuario
            FROM vehicle_checkin vc
            INNER JOIN users u ON vc.user_id = u.id
            WHERE vc.vehicle_plate = ?
            ORDER BY vc.created_at DESC
            LIMIT 1
        ");
        $stmt->execute([$plate]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    } catch (PDOException $e) {
        error_log("Erro ao buscar último check-in: " . $e->getMessage());
        return null;
    }
}


function carregarImagensUltimoUpload(PDO $pdo, string $plate): array
{
    $imagesByType = ['solicitacao' => [], 'devolucao' => []];
    $lastUploadDay = null;

    try {
        $stmtDate = $pdo->prepare("
            SELECT DATE(uploaded_at) AS last_upload_day
            FROM vehicle_uploads
            WHERE vehicle_plate = ?
            ORDER BY uploaded_at DESC
            LIMIT 1
        ");
        $stmtDate->execute([$plate]);
        $lastUploadRow = $stmtDate->fetch(PDO::FETCH_ASSOC);

        if ($lastUploadRow) {
            $lastUploadDay = $lastUploadRow['last_upload_day'];

            $stmtImgs = $pdo->prepare("
                SELECT upload_type, image_path
                FROM vehicle_uploads
                WHERE vehicle_plate = ? AND DATE(uploaded_at) = ?
                ORDER BY uploaded_at DESC
            ");
            $stmtImgs->execute([$plate, $lastUploadDay]);
            $uploads = $stmtImgs->fetchAll(PDO::FETCH_ASSOC);

            foreach ($uploads as $upload) {
                if (isset($imagesByType[$upload['upload_type']])) {
                    $imagesByType[$upload['upload_type']][] = $upload['image_path'];
                }
            }
        }
    } catch (PDOException $e) {
        error_log("Erro ao carregar imagens do veículo: " . $e->getMessage());
    }

    return [$imagesByType, $lastUploadDay];
}

$veiculo = carregarVeiculo($pdo, $plate);
if (!$veiculo) {
    echo '<p class="error-message">Veículo não encontrado.</p>';
    return;
}

$isResponsavel = ($veiculo['checked_in_by'] ?? null) == $usuarioLogadoId;
list($imagesByType, $lastUploadDay) = carregarImagensUltimoUpload($pdo, $plate);

// Define o base URL para as imagens no seu subdiretório
$baseUrl = '/speedlog';
?>


<fieldset class="vehicle-info">

    <h1><?= htmlspecialchars("{$veiculo['brand']} {$veiculo['model']} ({$veiculo['year']})") ?></h1>

    <div class="vehicle-info-header" role="region" aria-label="Informações do veículo">
        <fieldset>
            <legend>Veiculo</legend>
            <p><strong>Placa:</strong> <?= htmlspecialchars($veiculo['plate']) ?></p>
            <p><strong>Tipo:</strong> <?= htmlspecialchars($veiculo['type']) ?></p>
        </fieldset>

        <fieldset class="lastCheckin">
            <legend>Status</legend>
            <p><strong>Status:</strong> <?= htmlspecialchars($veiculo['status']) ?></p>
            <p><strong>Disponibilidade:</strong> <?= htmlspecialchars($veiculo['availability']) ?></p>
        </fieldset>
    </div>

    <fieldset>
        <legend>Last Checkin</legend>
        <?php if (!empty($veiculo['mileage'])): ?>
            <p><strong>Quilometragem no último check-in:</strong> <?= number_format($veiculo['mileage'], 0, ',', '.') ?> km</p>
        <?php endif; ?>

        <?php if (!empty($veiculo['fuel_level'])): ?>
            <p><strong>Nível de combustível:</strong> <?= htmlspecialchars($veiculo['fuel_level']) ?>%</p>
        <?php endif; ?>

        <?php if (!empty($veiculo['responsavel_nome'])): ?>
            <p><strong>Data do último check-in:</strong> <?= date('d/m/Y H:i', strtotime($veiculo['checked_at'])) ?></p>
        <?php endif; ?>
        <h4>
            Imagens do Último Upload
            (<?= $lastUploadDay ? date('d/m/Y', strtotime($lastUploadDay)) : 'Nenhuma imagem encontrada' ?>)
        </h4>


        <div class="images-container" role="region" aria-label="Imagens do último upload do veículo">
            <?php foreach ($imagesByType as $type => $images): ?>
                <section class="image-type-group" aria-label="Imagens de <?= htmlspecialchars($type) ?>">
                    <h5><?= ucfirst(htmlspecialchars($type)) ?></h5>
                    <?php if (!empty($images)): ?>
                        <div class="image-grid">
                            <?php foreach ($images as $imgPath): ?>
                                <?php
                                // Ajusta o src para incluir o baseUrl /speedlog
                                $imgSrc = $baseUrl . '/' . ltrim($imgPath, '/');
                                ?>
                                <img src="<?= htmlspecialchars($imgSrc) ?>" alt="Imagem de <?= ucfirst(htmlspecialchars($type)) ?>"
                                    loading="lazy" tabindex="0" role="button" aria-pressed="false" class="clickable-image" />
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="no-images">Nenhuma imagem de <?= htmlspecialchars($type) ?> no último upload.</p>
                    <?php endif; ?>
                </section>
            <?php endforeach; ?>
        </div>
    </fieldset>

    <div class="btn-group" role="group" aria-label="Ações do veículo">
        <?php if ($veiculo['availability'] === 'disponível'): ?>
            <button class="load-modal-content"
                data-modal-template-url="solicitarVeiculo.php?plate=<?= urlencode($veiculo['plate']) ?>"
                data-modal-title="Solicitação do Veículo <?= htmlspecialchars($veiculo['plate']) ?>">
                Solicitar Veículo
            </button>
        <?php elseif ($isResponsavel): ?>
            <button class="load-modal-content"
                data-modal-template-url="devolverVeiculo.php?plate=<?= urlencode($veiculo['plate']) ?>"
                data-modal-title="Devolução do Veículo <?= htmlspecialchars($veiculo['plate']) ?>">
                Devolver Veículo
            </button>
        <?php else: ?>
            <button disabled title="Veículo indisponível no momento.">
                🚫 Indisponível para Solicitação
            </button>
        <?php endif; ?>

        <div class="historicoBtn">
            <button class="load-modal-content"
                data-modal-template-url="historicoVeiculo.php?plate=<?= urlencode($veiculo['plate']) ?>"
                data-modal-title="Histórico do Veículo <?= htmlspecialchars($veiculo['plate']) ?>">
                Ver Histórico
            </button>
        </div>
    </div>
</fieldset>