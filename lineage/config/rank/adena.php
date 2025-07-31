<?php
include __DIR__ . '/../includes.php';


$result = $conn->query($queryAdena);
$rank = 1;


?>


<h1>Rank PvP</h1>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>Qtd</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): 
            
            ?>
            <tr>
            <td width="8px"><?= $rank++ ?></td>
                <td><?= htmlspecialchars($row['char_name']) ?></td>
                <td><?= $row_formated = number_format($row['kills'],0,",",",") ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
