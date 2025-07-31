<?php
// rank/clan.php
include __DIR__ . '/../../config/includes.php';  // ajuste o caminho conforme estrutura

$resultClan = $conn->query($queryClan);

if (!$resultClan) {
    echo '<p>Erro ao carregar dados de Clãs.</p>';
    return;
}

$medals = ['🥇', '🥈', '🥉'];
$rank = 1;
?>

<div class="rank-container">
    <h2 class="rank-title">🏆 Ranking de Clãs</h2>
    <table class="rank-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Clã</th>
                <th>Reputação</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $resultClan->fetch_assoc()): ?>
                <tr>
                    <td><?= $rank <= 3 ? $medals[$rank - 1] : '👑' ?></td>
                    <td><?= htmlspecialchars($row['clan_name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= number_format((int)$row['reputation_score']) ?></td>
                </tr>
                <?php $rank++; ?>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
