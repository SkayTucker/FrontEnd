<?php

class Stats {

	public static function TopPvP($limit) {
		
		$sql = DB::Executa("SELECT C.char_name, C.pvpkills, C.pkkills, C.online, C.onlinetime, D.name AS clan_name FROM characters AS C LEFT JOIN clan_subpledges AS D ON D.clan_id = C.clanid AND D.type = '0' WHERE C.accesslevel = '0' ORDER BY pvpkills DESC, pkkills DESC, onlinetime DESC, char_name ASC LIMIT ".$limit);
		return $sql;
		
	}
	
	public static function TopPk($limit) {
		
		$sql = DB::Executa("SELECT C.char_name, C.pvpkills, C.pkkills, C.online, C.onlinetime, D.name AS clan_name FROM characters AS C LEFT JOIN clan_subpledges AS D ON D.clan_id = C.clanid AND D.type = '0' WHERE C.accesslevel = '0' ORDER BY pkkills DESC, pvpkills DESC, onlinetime DESC, char_name ASC LIMIT ".$limit);
		return $sql;
		
	}
	
	public static function TopOnline($limit) {
		
		$sql = DB::Executa("SELECT C.char_name, C.pvpkills, C.pkkills, C.online, C.onlinetime, D.name AS clan_name FROM characters AS C LEFT JOIN clan_subpledges AS D ON D.clan_id = C.clanid AND D.type = '0' WHERE C.accesslevel = '0' ORDER BY onlinetime DESC, pvpkills DESC, pkkills DESC, char_name ASC LIMIT ".$limit);
		return $sql;
		
	}
	
	public static function TopLevel($limit) {
		
		$sql = DB::Executa("SELECT C.char_name, C.pvpkills, C.pkkills, C.online, C.onlinetime, CS.level, D.name AS clan_name FROM characters AS C LEFT JOIN clan_subpledges AS D ON D.clan_id = C.clanid AND D.type = '0' LEFT JOIN character_subclasses AS CS ON CS.char_obj_id = C.obj_Id AND CS.isBase = '1' WHERE C.accesslevel = '0' ORDER BY level DESC, exp DESC, onlinetime DESC, char_name ASC LIMIT ".$limit);
		return $sql;
		
	}
	
	public static function TopAdena($limit, $adnBillionItem) {
		$sql = "
		SELECT 
			C.char_name, 
			C.online, 
			C.onlinetime, 
			CS.level, 
			D.name AS clan_name, 
			(";
		
		if($adnBillionItem != 0) {
			$sql .= "
					IFNULL( (SELECT (SUM(I2.amount) * 1000000000) FROM items AS I2 WHERE I2.owner_id = C.obj_Id AND I2.item_type = '".$adnBillionItem."' GROUP BY I2.owner_id) , 0)
					+
				";
		}
				
		$sql .= "
				IFNULL( (SELECT SUM(I1.amount) FROM items AS I1 WHERE I1.owner_id = C.obj_Id AND I1.item_type = '57' GROUP BY I1.owner_id) , 0)
			) AS adenas
		FROM 
			characters AS C
		LEFT JOIN
			clan_subpledges AS D ON D.clan_id = C.clanid AND D.type = '0'
		LEFT JOIN
			character_subclasses AS CS ON CS.char_obj_id = C.obj_Id AND CS.isBase = '1'
		WHERE C.accesslevel = '0'
		ORDER BY
			adenas DESC, onlinetime DESC, char_name ASC
		LIMIT ".$limit;
		return DB::Executa($sql);
		
	}
	
	public static function TopClan($limit) {
		
		$sql = DB::Executa("
			SELECT
				CS.name AS clan_name,
				C.clan_level,
				C.reputation_score,
				A.ally_name,
				P.char_name,
				(SELECT COUNT(*) FROM characters WHERE clanid = C.clan_id) AS membros
			FROM
				clan_data AS C
			LEFT JOIN
				clan_subpledges AS CS ON CS.clan_id = C.clan_id
			LEFT JOIN
				ally_data AS A ON A.ally_id = C.ally_id
			LEFT JOIN
				characters AS P ON P.obj_Id = CS.leader_id
			ORDER BY
				C.clan_level DESC, C.reputation_score DESC, membros DESC
			LIMIT ".$limit."
		");
		return $sql;
		
	}

	public static function OlympiadRanking() {
		
		$sql = DB::Executa("
			SELECT 
				C.char_name, 
				C.online, 
				D.name AS clan_name, 
				O.class_id AS base, 
				O.points_current AS olympiad_points
			FROM
				oly_nobles AS O
			LEFT JOIN
				characters AS C ON C.obj_Id = O.char_id
			LEFT JOIN
				clan_subpledges AS D ON D.clan_id = C.clanid 
			ORDER BY olympiad_points DESC
		");
		return $sql;
		
	}
	
	public static function OlympiadAlloly_heroes() {
		
		$sql = DB::Executa("
			SELECT 
				C.char_name, 
				C.online, 
				D.name AS clan_name, 
				A.ally_name, 
				CS.class_id AS base, 
				H.count
			FROM
				oly_heroes AS H
			LEFT JOIN
				characters AS C ON C.obj_Id = H.char_id
			LEFT JOIN
				character_subclasses AS CS ON C.obj_Id = CS.char_obj_id AND CS.isBase = '1'
			LEFT JOIN
				clan_subpledges AS D ON D.clan_id = C.clanid 
			LEFT JOIN
				clan_data AS CD ON D.clan_id = C.clanid 
			LEFT JOIN
				ally_data AS A ON A.ally_id = CD.ally_id 
			WHERE
				H.played > 0 AND H.count > 0
			ORDER BY count DESC, base ASC, char_name ASC
		");
		return $sql;
		
	}
	
	public static function OlympiadCurrentoly_heroes() {
		
		$sql = DB::Executa("
			SELECT 
				C.char_name, 
				C.online, 
				D.name AS clan_name, 
				A.ally_name, 
				CS.class_id AS base
			FROM
				oly_heroes AS H
			LEFT JOIN
				characters AS C ON C.obj_Id = H.char_id
			LEFT JOIN
				character_subclasses AS CS ON C.obj_Id = CS.char_obj_id AND CS.isBase = '1'
			LEFT JOIN
				clan_subpledges AS D ON D.clan_id = C.clanid 
			LEFT JOIN
				clan_data AS CD ON D.clan_id = C.clanid 
			LEFT JOIN
				ally_data AS A ON A.ally_id = CD.ally_id 
			WHERE
				H.played > 0 AND H.count > 0
			ORDER BY base ASC
		");
		return $sql;
		
	}
	
	public static function GrandbossStatus() {
		
		$sql = DB::Executa("
			SELECT 
				B.bossId AS boss_id, 
				B.respawnDate AS respawn, 
				N.name, 
				N.level
			FROM
				epic_boss_spawn AS B
			INNER JOIN
				site_bosses AS N ON N.id = B.bossId
			ORDER BY respawn DESC, level DESC, name ASC
		");
		return $sql;
		
	}
	
	public static function RaidbossStatus() {
		
		$sql = DB::Executa("
			SELECT 
				B.id AS boss_id, 
				B.respawn_delay AS respawn, 
				N.name, 
				N.level
			FROM
				raidboss_status AS B
			INNER JOIN
				site_bosses AS N ON N.id = B.id
			ORDER BY respawn DESC, level DESC, name ASC
		");
		return $sql;
		
	}
	
	public static function Siege() {
		
		$sql = DB::Executa("
			SELECT
				W.id, 
				W.name, 
				W.siege_date AS sdate, 
				W.tax_percent AS stax, 
				P.char_name, 
				D.name AS clan_name, 
				A.ally_name
			FROM
				castle AS W
			LEFT JOIN
				clan_data AS C ON C.hasCastle = W.id
			LEFT JOIN
				clan_subpledges AS D ON D.clan_id = C.clan_id AND D.type = '0'
			LEFT JOIN
				ally_data AS A ON A.ally_id = C.ally_id
			LEFT JOIN
				characters AS P ON P.obj_Id = D.leader_id
		");
		return $sql;
		
	}
	
	public static function SiegeParticipants($castle_id) {
		
		$sql = DB::Executa("
			SELECT
				S.type, 
				C.name AS clan_name
			FROM
				siege_clans AS S
			LEFT JOIN
				clan_subpledges AS C ON C.clan_id = S.clan_id AND C.type = '0'
			WHERE
				S.residence_id = '".$castle_id."'
		");
		return $sql;
		
	}
	
	public static function BossJwlLoc($bossJwlIds) {
		
		$sql = DB::Executa("
		SELECT
			I.owner_id, 
			I.item_type AS item_id, 
			SUM(I.amount) AS count, 
			C.char_name, 
			P.name AS clan_name
		FROM
			items AS I
		INNER JOIN
			characters AS C ON C.obj_Id = I.owner_id
		LEFT JOIN
			clan_subpledges AS P ON P.clan_id = C.clanid AND P.type = '0'
		WHERE
			I.item_type IN (".$bossJwlIds.")
		GROUP BY
			I.owner_id, C.char_name, P.name, I.item_type
		ORDER BY
			count DESC, C.char_name ASC
		");
		return $sql;
		
	}
	
}
