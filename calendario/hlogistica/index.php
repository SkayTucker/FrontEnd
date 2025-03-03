<?php
require 'config/querys.php';

// Busca as etapas e tarefas
$query = "SELECT e.id AS etapa_id, e.titulo, t.id AS tarefa_id, t.descricao, t.concluido 
          FROM etapas e 
          LEFT JOIN tarefas t ON e.id = t.etapa_id 
          ORDER BY e.id, t.id";

$stmt = $pdo->prepare($query);
$stmt->execute();
$etapas = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $etapa_id = $row['etapa_id'];

    if (!isset($etapas[$etapa_id])) {
        $etapas[$etapa_id] = [
            'id' => $etapa_id,
            'titulo' => $row['titulo'],
            'tarefas' => []
        ];
    }

    if ($row['tarefa_id'] !== null) {
        $etapas[$etapa_id]['tarefas'][] = [
            'id' => $row['tarefa_id'],
            'descricao' => $row['descricao'],
            'concluido' => $row['concluido']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HLogistica Scrum Board</title>
    <script defer src="script.js"></script>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .etapa { margin-bottom: 20px; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
        .titulo { font-size: 18px; font-weight: bold; margin-bottom: 10px; }
        .tarefa { display: flex; align-items: center; margin-bottom: 5px; }
        .tarefa input { margin-right: 10px; }
    </style>
</head>
<body>
    <h1>📌 HLogistica Scrum Board</h1>

    <?php foreach ($etapas as $etapa): ?>
        <div class="etapa">
            <div class="titulo"><?= htmlspecialchars($etapa['titulo']) ?></div>
            <?php foreach ($etapa['tarefas'] as $tarefa): ?>
                <div class="tarefa">
                    <input type="checkbox" data-id="<?= $tarefa['id'] ?>" <?= $tarefa['concluido'] ? 'checked' : '' ?>>
                    <label><?= htmlspecialchars($tarefa['descricao']) ?></label>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</body>
</html>
