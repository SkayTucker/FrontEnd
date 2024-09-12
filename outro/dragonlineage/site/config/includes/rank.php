<?php
// Conexão com o banco de dados
include_once 'config.php';

// Verifique a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}


function exibirRank($conn, $query, $titulo, $nomeColuna, $top) {
    $result = $conn->query($query);

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
    while ($row = $result->fetch_assoc()) {
        $name = $row['char_name'];
        $value = $row[$nomeColuna];
        $objId = $row['obj_Id'];

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
$queryPvP = "SELECT char_name, pvpkills, obj_Id FROM characters WHERE accesslevel = 0 ORDER BY pvpkills DESC LIMIT $top";
exibirRank($conn, $queryPvP, "PvP", "pvpkills", $top);

// Rank por PK
$queryPK = "SELECT char_name, pkkills, obj_Id FROM characters WHERE accesslevel = 0 ORDER BY pkkills DESC LIMIT $top";
exibirRank($conn, $queryPK, "Pk", "pkkills", $top);

// Rank por Tempo Online
$queryOnlineTime = "SELECT char_name, onlinetime, obj_Id FROM characters WHERE accesslevel = 0 ORDER BY onlinetime DESC LIMIT $top";
exibirRank($conn, $queryOnlineTime, "Online", "onlinetime", $top);

// Rank por Raid Points
$queryRaidPoints = "SELECT char_name, points, char_id 
                    FROM character_raid_points 
                    INNER JOIN characters ON character_raid_points.char_id = characters.obj_Id 
                    WHERE accesslevel = 0 ORDER BY points DESC LIMIT $top";
exibirRank($conn, $queryRaidPoints, "Top Raid Points", "points", $top);

// Fechar a conexão com o banco de dados
$conn->close();
?>
