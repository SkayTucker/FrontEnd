<?php
$imgPath = __DIR__ . '../../../uploads/JKL123_Carro/user_17/2025-06-16/solicitacao/JKL123_Carro_user17_11-05_0.jpg';

if (file_exists($imgPath)) {
    header('Content-Type: image/jpeg');
    readfile($imgPath);
} else {
    echo "Arquivo não encontrado: $imgPath";
}
?>
