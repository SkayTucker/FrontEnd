<?php
include __DIR__ . '/../includes.php';

$resultClan = $conn->query($queryClan);

$resultCastle = $conn->query($queryCastle);

$rankClan = 1;
$rankCastle = 1;
?>
<div class="rank-container">
    <h6>🏆 Rank Clan 🏁</h6>
    <div class="rank-section">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Líder</th>
                    <th>Rep</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resultClan->fetch_assoc()): ?>
                    <tr>
                    <td><?= $rankClan++ ?></td>
                        <td><?= htmlspecialchars($row['clan_name']) ?></td>
                        <td><?= $row['char_name'] ?></td>
                    <td><?= $row['reputation_score'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
 
    <h6>🏰 Castelos 🏁</h6>
    <div class="rank-section">
        <table>
            <thead>
                <tr>
                    <th>Castelo</th>
                    <th>Clã</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resultCastle->fetch_assoc()): ?>
                    <tr>
                        <td width="8px"><?= htmlspecialchars($row['castle_name']) ?></td>
                        <td><?= $row['clan_name'] === 'Sem dono' ? 'Vazio' : htmlspecialchars($row['clan_name']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
