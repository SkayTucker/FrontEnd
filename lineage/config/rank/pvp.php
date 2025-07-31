<?php
// rank/pvp.php
include __DIR__ . '/../../config/includes.php';  // ajuste o caminho conforme sua estrutura

// Consulta ao banco para dados de PvP
$resultPvp = $conn->query($queryPvp);

if (!$resultPvp) {
    echo '<p>Erro ao carregar dados PvP.</p>';
    return;
}

function getRankingIcon(int $pos): string
{
    return match ($pos) {
        1 => '🥇',
        2 => '🥈',
        3 => '🥉',
        default => '👑',
    };
}
?>

<table class="rank-table">
    <thead>
        <tr>
            <th>Nome</th>
            <th>PvP Kills</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $pos = 1;
        while ($row = $resultPvp->fetch_assoc()):
        ?>
            <tr>
                <td><?= getRankingIcon($pos) . ' ' . htmlspecialchars($row['char_name'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= (int)$row['kills'] ?></td>
            </tr>
        <?php
            $pos++;
        endwhile;
        ?>
    </tbody>
</table>
