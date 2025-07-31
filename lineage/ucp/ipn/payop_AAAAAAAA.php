<?php

error_reporting(0);
ini_set('error_reporting', 0);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('default_charset', 'UTF-8');
date_default_timezone_set('America/Sao_Paulo');
header('HTTP/1.1 200 OK');

if(count($_POST) == 0){
	$postdata = @file_get_contents("php://input");
	if(!empty($postdata)) {
		$_POST = @json_decode($postdata,true);
	}
}

function saveLog($text, $dump=0) {
	$xpldName = explode('.', basename( __FILE__ ));
	$secretName = explode('_', $xpldName[0]);
	$f = fopen("logs/".$secretName[0]."_".date('m-Y')."__".md5($secretName[1]).".txt","a+");
	$t = date('d/m/Y H:i').": ".$text.($dump==1 ? " - Dump: ".strtr(print_r($_POST, true), array('  ' => ' ')) : "")."\r\n";
	fwrite($f, $t, strlen($t));
	fclose($f);
}

function vCode($content) {
	return addslashes(htmlentities(trim($content), ENT_QUOTES, 'ISO-8859-1'));
}

saveLog('GO!');

$invoice = (!empty($_POST['invoice']) ? $_POST['invoice'] : (!empty($_GET['invoice']) ? $_GET['invoice'] : ''));
if(empty($invoice)) {
	saveLog("Empty invoice notification! - Dump: POSTS: ".(!empty($_POST) ? print_r($_POST, true) : 'NO')." / GET: ".(!empty($_GET) ? print_r($_GET, true) : 'NO'));
	exit;
}

$transaction = (!empty($_POST['transaction']) ? $_POST['transaction'] : (!empty($_GET['transaction']) ? $_GET['transaction'] : ''));
if(empty($transaction)) {
	saveLog("Empty transaction data! - Dump: POSTS: ".(!empty($_POST) ? print_r($_POST, true) : 'NO')." / GET: ".(!empty($_GET) ? print_r($_GET, true) : 'NO'));
	exit;
}

$id = (!empty($invoice->id) ? $invoice->id : (!empty($invoice['id']) ? $invoice['id'] : 0));
if(empty($id)) {
	saveLog("Empty invoice ID! - Dump: POSTS: ".(!empty($_POST) ? print_r($_POST, true) : 'NO')." / GET: ".(!empty($_GET) ? print_r($_GET, true) : 'NO'));
	exit;
}

$state = (!empty($transaction->state) ? $transaction->state : (!empty($transaction['state']) ? $transaction['state'] : 0));
if(empty($state)) {
	saveLog("State invalid! - Dump: POSTS: ".(!empty($_POST) ? print_r($_POST, true) : 'NO')." / GET: ".(!empty($_GET) ? print_r($_GET, true) : 'NO'));
	exit;
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://payop.com/v1/invoices/'.$id);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = json_decode(curl_exec($ch));
curl_close($ch);

$data = !empty($response->data) ? $response->data : '';

if(empty($data)) {
	saveLog("Data error! (".print_r($response, true).") - Dump: POSTS: ".(!empty($_POST) ? print_r($_POST, true) : 'NO')." / GET: ".(!empty($_GET) ? print_r($_GET, true) : 'NO'));
	exit;
}

$tid = vCode($data->identifier);
$ref = vCode($data->orderIdentifier);
$price = vCode($data->amount);
$curr = vCode($data->currency);
$status = $state;

if(file_exists('../private/configs.php')) {
	require('../private/configs.php');
} else {
	saveLog("Require configs!", 0);
	exit;
}

if(file_exists('../../private/configs.php') && (!isset($host) || !isset($dbnm) || !isset($user) || !isset($pass))) {
	require('../../private/configs.php');
}

if(file_exists('../private/classes/DB.php')) {
	require('../private/classes/DB.php');
} else {
	require('../../private/classes/DB.php');
}

new DB($conMethod, $host, $user, $pass, $dbnm);

$d = DB::Executa("SELECT * FROM site_donations WHERE protocolo = '".$ref."' LIMIT 1");
if(count($d) == 0) {
	saveLog("Protocolo inexistente!", 1);
	exit;
}

$account = trim($d[0]['account']);
$coinsEntregar = intval(trim($d[0]['quant_coins']) + trim($d[0]['coins_bonus']));
$coinsEntregues = intval(trim($d[0]['coins_entregues']));
$personagem = trim($d[0]['personagem']);
$valor = trim($d[0]['valor']);
$currentStatus = intval(trim($d[0]['status']));
$currency = trim($d[0]['currency']);

switch($status) {
	case 1: $finalStatus = 1; break; // NEW
	case 2: $finalStatus = 3; break; // ACCEPTED
	case 3: $finalStatus = 5; break; // REJECTED
	case 4: $finalStatus = 1; break; // PENDING
	case 5: $finalStatus = 5; break; // FAILED
	default: $finalStatus = 1; break;
}

if($currentStatus == 4) {
	saveLog("A alteração de status da doação foi barrada pelo fato dela já ter sido processada e entregue.", 1);
	exit;
}

if($currentStatus == 2 && $finalStatus != 3) {
	$finalStatus = 2;
}

$updateOrder = DB::Executa("UPDATE site_donations SET ultima_alteracao = '".time()."', transaction_code = '".$tid."', status = '".$finalStatus."', status_real = '".$status."' WHERE protocolo = '".$ref."' LIMIT 1");
if(!$updateOrder) {
	saveLog("Não foi possível atualizar o status da transação! #1", 1);
	exit;
}

if($PayOp['autoDelivery'] != 1) {
	saveLog("Transação recebida e processada com sucesso! #1", 1);
	exit;
}

if($coinsEntregues != $coinsEntregar && $finalStatus == 3) {
	
	if(number_format($price, 2, '.', '') < number_format($valor, 2, '.', '')) {
		saveLog("O valor pago é inferior ao valor registrado!", 1);
		exit;
	}
	
	if($curr != $currency) {
		saveLog("Currency incorreto!", 1);
		exit;
	}
	
	$updateOrder = DB::Executa("UPDATE site_donations SET coins_entregues = '".$coinsEntregar."', status = '4' WHERE protocolo = '".$ref."' LIMIT 1");
	if(!$updateOrder) {
		saveLog("Não foi possível atualizar o status da transação! #2", 1);
		exit;
	}

	$checkExists = DB::Executa("SELECT * FROM site_balance WHERE account = '".$account."' LIMIT 1");
	if(count($checkExists) > 0) {
		$addBalance = DB::Executa("UPDATE site_balance SET saldo = (saldo+".$coinsEntregar.") WHERE account = '".$account."' LIMIT 1");
	} else {
		$addBalance = DB::Executa("INSERT INTO site_balance (account, saldo) VALUES ('".$account."', '".$coinsEntregar."')");
	}
	
	if($addBalance) {
		saveLog("Transação recebida e saldo entregue com sucesso!", 1);
		exit;
	} else {
		saveLog("Não foi possível concluir e entregar o saldo da transação!", 1);
		exit;
	}

}

saveLog("Transação recebida e processada com sucesso! #2", 1);

