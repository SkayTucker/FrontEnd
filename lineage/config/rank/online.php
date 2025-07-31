<?php
include __DIR__ . '/../includes.php'; 

$result = $conn->query($queryRankOnline);
$rank = 1;
?>

<h6>🏆 Rank de Tempo Online 🏆</h6>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>Tempo Online</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
            <td width="8px"><?= $rank++ ?></td>
                <td><?= htmlspecialchars($row['char_name']) ?></td>
                <td><?= $row['horas'] ?>h <?= $row['minutos'] ?>min</td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
