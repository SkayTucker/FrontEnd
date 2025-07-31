<?php
require_once '../src/app/config/includes.php';
require_once '../src/app/config/querys.php';

// Organize os dados por categorias
$dashboardGroups = [
    'Usuários' => [
        'Total de Usuários' => getTotalUsuarios($pdo),
    ],
    'Veículos' => [
        'Total de Veículos' => getTotalVeiculos($pdo),
        'Veículos em Uso' => getVeiculosEmUso($pdo),
        'Veículos Disponíveis' => getVeiculosDisponiveis($pdo),
    ],
    'Operações' => [
        'Check-ins Ativos' => getCheckinsAtivos($pdo),
        'Histórico Total' => getHistoricoTotal($pdo),
    ]
];
?>

<section class="dashboard">
    <fieldset>
        <legend>
            <h1 class="dashboard-title">Painel Administrativo</h1>
        </legend>
        <div class="dashboard-content">

            <?php foreach ($dashboardGroups as $groupTitle => $cards): ?>
                <fieldset class="dashboard-group">
                    <legend><?= htmlspecialchars($groupTitle) ?></legend>
                    <div class="dashboard-cards">
                        <?php foreach ($cards as $label => $value): ?>
                            <div class="card">
                                <h3><?= htmlspecialchars($label) ?></h3>
                                <p><?= htmlspecialchars($value) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </fieldset>
            <?php endforeach; ?>

        </div>
    </fieldset>
</section>

<!-- Modal fixo global -->
<div id="modal-template" class="modal hidden" role="dialog" aria-modal="true" aria-labelledby="modal-title">