<?php

function getTotalUsuarios(PDO $pdo) {
    return $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
}

function getTotalVeiculos(PDO $pdo) {
    return $pdo->query("SELECT COUNT(*) FROM vehicles")->fetchColumn();
}

function getVeiculosEmUso(PDO $pdo) {
    return $pdo->query("SELECT COUNT(*) FROM vehicles WHERE availability = 'em uso'")->fetchColumn();
}

function getVeiculosDisponiveis(PDO $pdo) {
    return $pdo->query("SELECT COUNT(*) FROM vehicles WHERE availability = 'disponível'")->fetchColumn();
}

function getCheckinsAtivos(PDO $pdo) {
    return $pdo->query("SELECT COUNT(*) FROM vehicle_checkin WHERE status = 'em uso'")->fetchColumn();
}

function getHistoricoTotal(PDO $pdo) {
    return $pdo->query("SELECT COUNT(*) FROM vehicle_history")->fetchColumn();
}
