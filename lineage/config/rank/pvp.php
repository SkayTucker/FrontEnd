<?php
include __DIR__ . '/../includes.php';

// Consulta para Rank PvP (jogadores com mais PvP kills)
$resultPvp = $conn->query($queryPvp);

// Consulta para Rank PK (jogadores com mais PK kills)
$resultPk = $conn->query($queryPk);

$rankPvp = 1;
$rankPk = 1;
?>
<div class="rank-container">
    <h6>🏆 Rank PvP ⚔</h6>
    <div class="rank-section">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Kills</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resultPvp->fetch_assoc()): ?>
                    <tr>
                    <td width="8px"><?= $rankPvp++ ?></td>
                        <td><?= htmlspecialchars($row['char_name']) ?></td>
                        <td width="8px"><?= $row['kills'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <h6>🏆 Rank Pk ☠</h6>
    <div class="rank-section">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Kills</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resultPk->fetch_assoc()): ?>
                    <tr>
                    <td width="8px"><?= $rankPk++ ?></td>
                        <td><?= htmlspecialchars($row['char_name']) ?></td>
                        <td width="8px"><?= $row['kills'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
