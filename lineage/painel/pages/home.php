<?php
session_start();
include '../../config/includes.php';

$user_id = $_SESSION['user_id'];

// Consulta os personagens do usuário (incluindo o campo 'sex')
$sql = "SELECT char_name, level, curHp, curMp, curCp, maxHp,maxCp, maxMp, base_class, classid, sex 
        FROM characters 
        WHERE account_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$personagens = [];
while ($row = $result->fetch_assoc()) {
    $personagens[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Meus Personagens</title>
    <!-- Se preferir, inclua seu arquivo CSS principal aqui -->
    <link rel="stylesheet" href="../../css/styleDashboard.css">
</head>
<body>
    <h2>Meus Personagens</h2>
    <div class="charMenu">
        <ul>
            <?php foreach ($personagens as $personagem):
                // Determina a raça a partir do classid
                $classid = (int)$personagem['classid'];
                if ($classid == 0) {
                    $race = "humanFighter";
                } else if ($classid == 10) {
                    $race = "humanMistico";
                } else if ($classid == 18) {
                    $race = "elfFighter";
                } elseif ($classid == 26) {
                    $race = "elfMistico";
                } else if ($classid == 31) {
                    $race = "darkelfFighter";
                } else if ($classid == 38) {
                    $race = "darkelfMistico";
                }  else {
                    $race = "default";
                }
                
                // Determina o sexo: assumindo que 0 = masculino e 1 = feminino
                $gender = ($personagem['sex'] == 0) ? "male" : "female";
                
                // Monta o nome do arquivo de imagem conforme raça e sexo
                if ($race != "default") {
                    // Exemplo: human_male_fighter.png ou human_female_fighter.png
                    $image = "{$race}_{$gender}.png";
                } else {
                    $image = "default.png";
                }
                
                // Para as barras de status, vamos supor um valor máximo fixo de 1000 (ajuste conforme necessário)
                $maxCp = $personagem['maxCp'];
                $maxHp = $personagem['maxHp'];
                $maxMp = $personagem['maxMp'];
                $cpPercent = min(100, ($personagem['curCp'] / $maxCp) * 100);
                $hpPercent = min(100, ($personagem['curHp'] / $maxHp) * 100);
                $mpPercent = min(100, ($personagem['curMp'] / $maxMp) * 100);
            ?>
            <li>
                <div class="charHeader">
                    <img src="./img/char/<?php echo $image; ?>" alt="Imagem de <?php echo htmlspecialchars($personagem['char_name']); ?>">
                    <div>
                        <strong><?php echo htmlspecialchars($personagem['char_name']); ?></strong>
                        <strong>Level: <?php echo $personagem['level']; ?></strong>
                    </div>
                </div>
                <div class="charBody" style="font-size: 14px; text-align: center;">
                <div class="bar cp">
                        <div class="bar-fill" style="width: <?php echo $cpPercent; ?>%;"><?php echo $personagem['curCp']; ?> / <?php echo $personagem['maxCp']; ?></div>
                    </div>                    
                    <div class="bar hp">
                        <div class="bar-fill" style="width: <?php echo $hpPercent; ?>%;"><?php echo $personagem['curHp']; ?> / <?php echo $personagem['maxHp']; ?></div>
                    </div>
                    <div class="bar mp">
                        <div class="bar-fill" style="width: <?php echo $mpPercent; ?>%;"> <?php echo $personagem['curMp']; ?> / <?php echo $personagem['maxMp']; ?></div>
                    </div>
                    <p>Classe Base: <?php echo $personagem['classid']; ?> Classe Atual: <?php echo htmlspecialchars($personagem['base_class']); ?></p>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
