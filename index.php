<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Lista de Diretórios</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f0f0f0;
        }
        .cards {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 15px;
            width: 200px;
            text-align: center;
            cursor: pointer;
            transition: box-shadow 0.2s;
        }
        .card:hover {
            box-shadow: 0 4px 14px rgba(0,0,0,0.25);
        }
    </style>
</head>
<body>

<h1>Diretórios em htdocs</h1>

<div class="cards">
<?php
$path = __DIR__; // pasta onde o index.php está

// abrir o diretório
if ($handle = opendir($path)) {
    while (false !== ($entry = readdir($handle))) {
        // ignorar arquivos '.' e '..' e garantir que seja diretório
        if ($entry != "." && $entry != ".." && is_dir($path . DIRECTORY_SEPARATOR . $entry)) {
            echo "<div class='card' onclick=\"window.location.href='$entry'\">"
                . "<h2>$entry</h2>"
                . "</div>";
        }
    }
    closedir($handle);
}
?>
</div>

</body>
</html>
