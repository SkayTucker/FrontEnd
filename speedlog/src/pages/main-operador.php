<?php
require_once '../src/app/config/includes.php';
require_once '../src/app/config/querys.php';
verificarAutenticacao();
try {
    $stmt = $pdo->query("
        SELECT plate, brand, model, year, type, mileage, status, availability, photos_dir
        FROM vehicles
        ORDER BY model
    ");
    $veiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erro ao carregar veículos: " . $e->getMessage());
    $veiculos = [];
}

$emUso = count(array_filter($veiculos, fn($v) => $v['availability'] === 'em uso'));
$disponiveis = count(array_filter($veiculos, fn($v) => $v['availability'] === 'disponível'));
$manutencao = count(array_filter($veiculos, fn($v) => $v['availability'] === 'manutenção'));
?>

<div class="vehicles-container">
    <fieldset class="menuVehicles-header">
        <div class="card">
            <h3>Veículos em Uso</h3>
            <p><?= $emUso ?></p>
        </div>
        <div class="card">
            <h3>Disponíveis</h3>
            <p><?= $disponiveis ?></p>
        </div>
        <div class="card">
            <h3>Em Manutenção</h3>
            <p><?= $manutencao ?></p>
        </div>
    </fieldset>

    <section class="menuVehicles-list">
        <fieldset class="menuVehicles-content">
            <?php foreach ($veiculos as $v): ?>
                <div class="menuVehicles-info <?= htmlspecialchars(str_replace(' ', '', $v['availability'])) ?>"
                    data-modal-template-url="menuVeiculo.php?plate=<?= urlencode($v['plate']) ?>"
                    data-modal-title="Menu do Veículo <?= htmlspecialchars($v['plate']) ?>">
                    <img src="<?= IMAGES_URL . '/' . (!empty($v['photos_dir']) ? htmlspecialchars($v['photos_dir']) : 'corolla.jpg') ?>"
                        alt="Foto do veículo">
                    <h3><?= htmlspecialchars($v['brand']) ?> <?= htmlspecialchars($v['model']) ?></h3>
                </div>
            <?php endforeach; ?>
        </fieldset>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const statusTexts = {
            'disponível': 'Disponível',
            'emuso': 'Em Uso',
            'manutenção': 'Em Manutenção',
            'indisponível': 'Indisponível'
        };

        document.querySelectorAll('.menuVehicles-info').forEach(card => {
            let availability = '';
            if (card.classList.contains('disponível')) availability = 'disponível';
            else if (card.classList.contains('emuso')) availability = 'emuso';
            else if (card.classList.contains('manutenção')) availability = 'manutenção';
            else if (card.classList.contains('indisponível')) availability = 'indisponível';

            if (availability === 'indisponível') {
                card.style.pointerEvents = 'none'; // Impede cliques
                card.style.opacity = '0.6'; // Visualmente mais opaco
                card.style.cursor = 'not-allowed'; // Cursor de bloqueio
                card.title = 'Este veículo está indisponível no momento.'; // Ajuda acessível
            }


            // Container para as colunas
            const container = document.createElement('div');
            container.classList.add('container-columns'); // opcional para estilo futuro

            // Coluna status
            const statusCol = document.createElement('div');
            statusCol.classList.add('status-col');
            statusCol.textContent = statusTexts[availability] || '';

            // Coluna conteúdo
            const contentCol = document.createElement('div');
            contentCol.classList.add('content-col');

            // Mover todos os filhos do card para contentCol
            while (card.firstChild) {
                contentCol.appendChild(card.firstChild);
            }

            // Limpar e montar estrutura
            card.innerHTML = '';
            container.appendChild(statusCol);
            container.appendChild(contentCol);
            card.appendChild(container);
        });



    });
</script>