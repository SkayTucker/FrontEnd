<?php
// historicoVeiculo.php

require_once '../app/config/includes.php';
verificarAutenticacao();

if (empty($_GET['plate'])) {
    echo "<p style='color:red;'>Placa não informada.</p>";
    exit;
}

$plate = $_GET['plate'];

try {
    $stmt = $pdo->prepare("
        SELECT 
            id, checkin_id, latitude, longitude, endereco, 
            quilometragem_inicial, quilometragem_final, devolvido_em, observacoes
        FROM vehicle_checkin_details
        WHERE vehicle_plate = ?
        ORDER BY id DESC
    ");
    $stmt->execute([$plate]);
    $historico = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erro ao buscar histórico do veículo $plate: " . $e->getMessage());
    echo "<p style='color:red;'>Erro ao buscar dados do histórico.</p>";
    exit;
}

if (empty($historico)) {
    echo "<p>📭 Nenhum check-in registrado para o veículo <strong>" . htmlspecialchars($plate) . "</strong>.</p>";
    return;
}

// Função para truncar texto longo com tooltip
function resumoTexto($texto, $max = 40)
{
    $texto = trim($texto);
    if (strlen($texto) > $max) {
        $curto = htmlspecialchars(mb_substr($texto, 0, $max)) . '...';
        return "<span title=\"" . htmlspecialchars($texto) . "\">{$curto}</span>";
    }
    return htmlspecialchars($texto);
}
?>
<fieldset>
    <legend>
        <h3>Histórico de Check-ins — Veículo <strong><?= htmlspecialchars($plate) ?></strong></h3>
    </legend>
    <table class="table-historico">
        <thead>
            <tr>
                <th>CID</th>
                <th>Km Inicial</th>
                <th>Km Final</th>
                <th>Devolução</th>
                <th>Localização</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($historico as $item): ?>
                <tr>
                    <td><?= $item['checkin_id'] ?></td>
                    <td><?= number_format($item['quilometragem_inicial'], 0, ',', '.') ?> km</td>
                    <td>
                        <?=
                        isset($item['quilometragem_final']) && is_numeric($item['quilometragem_final'])
                            ? number_format($item['quilometragem_final'], 0, ',', '.') . ' km'
                            : '-';
                        ?>
                    </td>
                    <td>
                        <?=
                        !empty($item['devolvido_em'])
                            ? date('d/m/Y H:i', strtotime($item['devolvido_em']))
                            : '-';
                        ?>
                    </td>
                    <td><?= $item['endereco'] ? resumoTexto($item['endereco']) : '-' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</fieldset>