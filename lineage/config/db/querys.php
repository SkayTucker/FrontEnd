<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclui config/includes.php apenas se existir
$include_path = __DIR__ . '/dbconnect.php'; // Caminho correto
if (file_exists($include_path)) {
    include $include_path;
} else {
    die("Erro: Arquivo 'includes.php' não encontrado.");
}

$queryPvp = "SELECT char_name, pvpkills AS kills FROM characters ORDER BY pvpkills DESC LIMIT 5";
$queryPk = "SELECT char_name, pkkills AS kills FROM characters ORDER BY pkkills DESC LIMIT 5";
$queryAdena = "SELECT characters.char_name, items.item_id, items.count AS kills
               FROM items 
               INNER JOIN characters 
               ON items.owner_id=characters.charId ORDER BY count DESC LIMIT 10";
$queryPlayersOnline = "SELECT COUNT(*) AS total_online FROM characters WHERE online = 1";
$queryRankOnline = "SELECT char_name, FLOOR(onlinetime / 3600) AS horas, FLOOR((onlinetime % 3600) / 60) AS minutos 
                    FROM characters 
                    ORDER BY onlinetime DESC LIMIT 10";

$queryClan = "SELECT characters.char_name, clan_data.clan_name, clan_data.clan_level, clan_data.reputation_score
              FROM clan_data
              INNER JOIN characters
              ON clan_data.leader_id=characters.charId ORDER BY reputation_score DESC LIMIT 3";                    

$queryCastle = "SELECT castle.name AS castle_name, castle.siegeDate, COALESCE(clan_data.clan_name, 'Sem dono') AS clan_name
                FROM castle
                LEFT JOIN clan_data ON clan_data.hasCastle = castle.id
                ORDER BY castle.siegeDate DESC";

?>