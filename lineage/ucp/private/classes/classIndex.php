<?php

class Index
{

	public static function accData($login)
	{

		$sql = DB::Executa("SELECT *, (SELECT COUNT(*) FROM characters WHERE account_name = '" . $login . "') AS chars FROM accounts WHERE login = '" . $login . "' LIMIT 1");
		return $sql;

	}

	public static function lastLogins($login)
	{

		$sql = DB::Executa("SELECT * FROM site_ucp_lastlogins WHERE login = '" . $login . "' ORDER BY logdate DESC LIMIT 5");
		return $sql;

	}

	public static function findChars($login)
	{
		return DB::Executa("
        SELECT charId, char_name, level, classid
        FROM characters
        WHERE account_name = '" . $login . "'
        ORDER BY char_slot
        LIMIT 7
    ");
	}


}
