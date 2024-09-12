<?php
// Conexão com o banco de dados
require_once 'config.php';

function exibirRank($pdo, $query, $titulo, $nomeColuna, $top) {
    $result = $pdo->query($query);

    echo "
    <div class='rankStats'>
        <h3>$titulo</h3>
        <table border='1' align='center' cellpadding='5'>
        <tr> 
        <th>Posição</th>
        <th>Nome</th> 
        <th>$nomeColuna</th>
        </tr>";

    $posicao = 1;
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $name = $row['char_name'];
        $value = $row[$nomeColuna];

        if ($nomeColuna === "onlinetime") {
            $value = formatarTempoOnline($value);
        }

        // Adiciona medalhas aos três primeiros
        $medal = '';
        if ($posicao === 1) {
            $medal = '🥇';
        } elseif ($posicao === 2) {
            $medal = '🥈';
        } elseif ($posicao === 3) {
            $medal = '🥉';
        }

        // Define a cor com base na posição
        $cor = "rgba(255, 255, 255, " . (1 - ($posicao / $top)) . ")";

        echo "<tr style='background-color: $cor;'><td align='center'>$medal $posicao</td><td align='center'><span>$name</span></td><td align='center'> <strong>$value</strong></td></tr>";
        $posicao++;
    }

    echo "</table></div><br>";
}

// Função para formatar o tempo online
function formatarTempoOnline($tempoOnline) {
    $dias = floor($tempoOnline / (60 * 60 * 24));
    $horas = floor(($tempoOnline % (60 * 60 * 24)) / (60 * 60));
    return "$dias D, $horas H";
}

$top = 7; // Número máximo de jogadores a serem exibidos

// Rank por PvP
$queryPvP = "SELECT char_name, pvpkills FROM characters WHERE accesslevel = 0 ORDER BY pvpkills DESC LIMIT $top";
exibirRank($pdo, $queryPvP, "PvP", "pvpkills", $top);

// Rank por PK
$queryPK = "SELECT char_name, pkkills FROM characters WHERE accesslevel = 0 ORDER BY pkkills DESC LIMIT $top";
exibirRank($pdo, $queryPK, "Pk", "pkkills", $top);

// Rank por Tempo Online
$queryOnlineTime = "SELECT char_name, onlinetime FROM characters WHERE accesslevel = 0 ORDER BY onlinetime DESC LIMIT $top";
exibirRank($pdo, $queryOnlineTime, "Online", "onlinetime", $top);

// Rank por Raid Points
$queryRaidPoints = "SELECT char_name, points 
                    FROM character_raid_points 
                    INNER JOIN characters ON character_raid_points.char_id = characters.obj_Id 
                    WHERE accesslevel = 0 ORDER BY points DESC LIMIT $top";
exibirRank($pdo, $queryRaidPoints, "Raid Points", "points", $top);

// Não é necessário fechar a conexão quando se usa PDO

?>
