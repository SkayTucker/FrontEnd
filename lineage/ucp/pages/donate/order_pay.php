<?php if((!$indexing) || ($logged != 1)) { exit; }
require('private/classes/classDonate.php');
?>

<ul class="breadcrumb">
	<li><a href='./?module=donate&page=add'><i class='fa fa-money'></i> <?php echo $LANG[12039]; ?></a></li>
	<li><a href='./?module=donate&page=orders'><?php echo $LANG[10015]; ?></a></li>
	<li><?php echo $LANG[10052]; ?></li>
</ul>

<?php

$protocolo = !empty($_GET['f']) ? intval($_GET['f']) : 0;

if(empty($protocolo)) { echo "<script>document.location.replace('./?module=donate&page=orders');</script>"; exit; }

$donation = Donate::findDonation($_SESSION['acc'], $protocolo);
if(count($donation) > 0) {
	
	$mpxpl = explode('_', $donation[0]['metodo_pgto']);
	$metodo_pgto = $mpxpl[0];
	
	echo "
	<h1>".$LANG[10052]."</h1>
	<div class='pddInner'>
		<b>".$LANG[10029].":</b> ".$donation[0]['protocolo']."<br />
		<b>".$LANG[10030].":</b> ".$donation[0]['quant_coins']."<br />
		<b>".$LANG[10031].":</b> ".$donation[0]['coins_bonus']."<br />
		<b>".$LANG[10032].":</b> ".($donation[0]['quant_coins']+$donation[0]['coins_bonus'])."<br />
		<b>".$LANG[10034].":</b> ".obtainCurrencySymbol($donation[0]['currency'])." ".number_format(trim($donation[0]['valor']), 2, ',', '.')." (".$donation[0]['currency'].")<br />
		<b>".$LANG[10035].":</b> ".date('d F, Y H:i', $donation[0]['data'])."<br />
		<b>".$LANG[10036].":</b> ".(!empty($donation[0]['ultima_alteracao']) ? date('d/m/Y H:i', $donation[0]['ultima_alteracao']) : $LANG[10039])."<br />
		<b>".$LANG[10037].":</b> ".$metodo_pgto."<br />
		<b>".$LANG[10038].":</b> ".obtainOrderStatusName($donation[0]['status'])."<br /><br />
		".$LANG[10045]."
	</div>
	";
	
	if($donation[0]['status'] == 1) {
		
		$donateDesc = $LANG[10052]." ".$donation[0]['protocolo']." - ".$donation[0]['quant_coins']." ".$coinName;
		
		switch(strtolower($metodo_pgto)) {
			
			case 'pagseguro':
			
				echo "
			    <form target='_blank' method='POST' action='".(!empty($PagSeguro['testando']) ? 'https://sandbox.pagseguro.uol.com.br/v2/checkout/payment.html' : 'https://pagseguro.uol.com.br/v2/checkout/payment.html')."'>  
		            <input name='receiverEmail' value='".$PagSeguro['email']."' type='hidden' />  
		            <input name='currency' value='".$donation[0]['currency']."' type='hidden' />  
		            <input name='itemId1' value='1' type='hidden' />  
		            <input name='itemDescription1' value='".$donateDesc."' type='hidden' />  
		            <input name='itemAmount1' value='".number_format(trim($donation[0]['valor']), 2, '.', '')."' type='hidden' />  
		            <input name='itemQuantity1' value='1' type='hidden' />  
		            <input name='reference' value='".$donation[0]['protocolo']."' type='hidden' />
		            <input type='submit' value='".$LANG[10042]."' class='default big' style='margin-left:20px;' />
		        </form>
		        ";
				
			break;
		
			case 'banking':
			
				echo "
				<h1>".$LANG[10040]."</h1>
				<div class='pddInner'>
					".$LANG[15005].":<br /><br />
					".$Banking['bank_dados']."
					<br /><br />
					<div class='rmsg warn'>".$LANG[15004].": <b>".$donateEmail."</b></div>
				</div>
				";
				
			break;
		
			case 'picpay':
			
				echo "
				<h1>".$LANG[10040]."</h1>
				<div class='pddInner'>
					PicPay: <b>".$PicPay['name']."</b><br />
					Download app: <a target='_blank' href='https://www.picpay.com/site/download'>https://www.picpay.com/site/download</a>
					<br /><br />
					<div class='rmsg warn'>".$LANG[15004].": <b>".$donateEmail."</b></div>
				</div>
				";
				
			break;
			
			case 'pix':
			
				echo "
				<h1>".$LANG[10040]."</h1>
				<div class='pddInner'>
				<b>".$Pix['pix_dados']."</b><br />

					<center><a href='https://nubank.com.br/pagar/1n6mxn/1u9LXOgW6b'><img src='imgs/pix.jpg' /></a></center>
					<br /><br />
					<div class='rmsg warn'>".$LANG[15004].": <b>".$donateEmail."</b></div>
				</div>
				";
				
			break;
		
			case 'paypal':
			
				echo "
				<form target='_blank' method='POST' action='https://www.paypal.com/cgi-bin/webscr'>
					<input type='hidden' name='cmd' value='_xclick' />
					<input type='hidden' name='business' value='".$PayPal['business_email']."' />
					<input type='hidden' name='currency_code' value='".$donation[0]['currency']."' />
					<input type='hidden' name='item_name' value='".$donateDesc."' />
					<input type='hidden' name='amount' value='".number_format(trim($donation[0]['valor']), 2, '.', '')."' />
					<input type='hidden' name='quantity' value='1' />
					<input type='hidden' name='custom' value='".$donation[0]['protocolo']."' />
					<input type='submit' value='".$LANG[10042]."' class='default big' style='margin-left:20px;' />
				</form>
				";
				
			break;
		
			case 'mercadopago':
			
				require_once('private/mp/mercadopago.php');
				
				$mp = new MP(trim($MercadoPago['client_id']), trim($MercadoPago['client_secret']));
				
				$preference_data = array(
					"external_reference" => $donation[0]['protocolo'],
					"items" => array(
						array(
							"title" => "".$donateDesc."",
							"quantity" => 1,
							"currency_id" => "".$donation[0]['currency']."",
							"unit_price" => ceil(trim($donation[0]['valor']))
						)
					)
				);
				
				$preference = $mp->create_preference($preference_data);
		
				echo "
				<a href='".$preference['response']['init_point']."' name='MP-Checkout' class='default big' style='margin-left:20px;'>".$LANG[10042]."</a>
				<script type='text/javascript' src='//resources.mlstatic.com/mptools/render.js'></script>
				";
				
			break;
		
			case 'paygol':
			
				echo "
				<form target='_blank' method='POST' action='https://www.paygol.com/pay' >
					<input type='hidden' name='pg_serviceid' value='".trim($PayGol['service_id'])."'>
					<input type='hidden' name='pg_currency' value='".$donation[0]['currency']."'>
					<input type='hidden' name='pg_name' value='".$donateDesc."'>
					<input type='hidden' name='pg_custom' value='".$donation[0]['protocolo']."'>
					<input type='hidden' name='pg_price' value='".number_format(ceil($donation[0]['valor']), 2, '.', '')."'>
					<input type='submit' value='".$LANG[10042]."' class='default big' style='margin-left:20px;' />
				</form>
				";
				
			break;
		
			case 'webmoney':
				
				echo "
				<form target='_blank' method='POST' action='https://merchant.wmtransfer.com/lmi/payment.asp'>
					<input type='hidden' name='LMI_PAYMENT_AMOUNT' value='".number_format($donation[0]['valor'], 2, '.', '')."'>
					<input type='hidden' name='LMI_PAYMENT_DESC' value='".$donateDesc."'>
					<input type='hidden' name='LMI_PAYMENT_NO' value='".$donation[0]['protocolo']."'>
					<input type='hidden' name='LMI_PAYEE_PURSE' value='".$WebMoney['merch_purse']."'>
					<input type='submit' value='".$LANG[10042]."' class='default big' style='margin-left:20px;' />
				</form>
				<br /><br />
				<div class='rmsg warn'>".$LANG[15004].": <b>".$donateEmail."</b></div>
				";
				
			break;
		
			case 'payza':
				
				echo "
				<form target='_blank' method='POST' action='https://secure.payza.com/checkout' >
					<input type='hidden' name='ap_merchant' value='".$Payza['email']."'/>
					<input type='hidden' name='ap_purchasetype' value='item'/>
					<input type='hidden' name='ap_itemname' value='".$donation[0]['quant_coins']." ".trim($coinName)."'/>
					<input type='hidden' name='ap_description' value='".$donateDesc."'/>
					<input type='hidden' name='ap_amount' value='".number_format($donation[0]['valor'], 2, '.', '')."'/>
					<input type='hidden' name='ap_currency' value='".$donation[0]['currency']."'/>
					<input type='hidden' name='ap_itemcode' value='".$donation[0]['protocolo']."'/>
					<input type='submit' value='".$LANG[10042]."' class='default big' style='margin-left:20px;' />
				</form>
				";
				
			break;
		
			case 'skrill':
				
				echo "
				<form target='_blank' method='POST' action='https://pay.skrill.com'>
					<input type='hidden' name='pay_to_email' value='".$Skrill['email']."'>
					<input type='hidden' name='language' value='EN'>
					<input type='hidden' name='amount' value='".number_format($donation[0]['valor'], 2, '.', '')."'>
					<input type='hidden' name='currency' value='".$Skrill['currency']."'>
					<input type='hidden' name='detail1_description' value='".$donateDesc."'>
					<input type='hidden' name='merchant_fields' value='protocol'>
					<input type='hidden' name='protocol' value='".$donation[0]['protocolo']."'>
					<input type='submit' value='".$LANG[10042]."' class='default big' style='margin-left:20px;' />
				</form>
				<br /><br />
				<div class='rmsg warn'>".$LANG[15004].": <b>".$donateEmail."</b></div>
				";
				
			break;
		
			case 'payop':
				
				// payop
				
				$findAccEmail = DB::Executa("SELECT email FROM accounts WHERE login = '".trim($donation[0]['account'])."' LIMIT 1");
				if(count($findAccEmail) != 0) {
					$accEmail = $findAccEmail[0]['email'];
				}
				if(empty($accEmail)) {
					$accEmail = 'non-existent-email@payop.com';
				}
				
				$order = array(
					'id' => ''.trim($donation[0]['protocolo']).'',
					'amount' => ''.trim($donation[0]['valor']).'',
					'currency' => ''.trim($PayOp['currency']).'',
				);
				ksort($order, SORT_STRING);
				$dataSet = array_values($order);
				array_push($dataSet, $PayOp['secret_key']);
				$signature = hash('sha256', implode(':', $dataSet));
				
				$jsonConstruct = array(
					'publicKey' => trim($PayOp['public_key']),
					'order' => array(
						'id' => ''.trim($donation[0]['protocolo']).'',
						'amount' => ''.trim($donation[0]['valor']).'',
						'currency' => ''.$PayOp['currency'].'',
						'items' => array(
							'id' => ''.trim($donation[0]['protocolo']).'',
							'name' => ''.$donation[0]['quant_coins'].' '.$coinName.'',
							'price' => ''.trim($donation[0]['valor']).''
						),
						'description' => ''.$donateDesc.''
					),
					'payer' => array(
						'email' => ''.$accEmail.'',
						'phone' => '',
						'name' => ''.trim($donation[0]['account']).''
					),
					'language' => 'en',
					'resultUrl' => 'http://'.$panel_url.'/?module=donate&page=order_pay&f='.trim($donation[0]['protocolo']).'#success',
					'failPath' => 'http://'.$panel_url.'/?module=donate&page=order_pay&f='.trim($donation[0]['protocolo']).'#fail',
					'signature' => ''.trim($signature).''
				);
				$jsonData = json_encode($jsonConstruct);
				
				$authorization = "Authorization: Bearer ".$PayOp['JWTToken'];
				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://payop.com/v1/invoices/create');
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($jsonData), $authorization));  
				curl_setopt($ch, CURLOPT_TIMEOUT, 30);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
				
				$response = json_decode(curl_exec($ch));
				curl_close($ch);
				
				$invoiceID = !empty($response->data) ? $response->data : '';
				
				if(!empty($invoiceID)) {
					
					$payButton = "<a target='_blank' href='https://payop.com/en/payment/invoice-preprocessing/".trim($invoiceID)."' class='default big'>".$LANG[10042]."</a>";
					
				} else {
					
					$payButton = "Fail! Please, contact admin. #PAYBUTTON<br /><pre>".print_r($response, true)."</pre>";
					
				}
			
				echo "
				<h1>".$LANG[10040]."</h1>
				<div class='pddInner'>
				    ".$LANG[39013]."<br /><br />
				    
					".$payButton."
				    
				</div>
				";
				
			break;
			
			
			
		
			case 'paymentwall':
			
				// PaymentWall
				
				$findAccEmail = DB::Executa("SELECT email FROM accounts WHERE login = '".trim($donation[0]['account'])."' LIMIT 1");
				if(count($findAccEmail) != 0) {
					$accEmail = $findAccEmail[0]['email'];
				}
				if(empty($accEmail)) {
					$accEmail = 'non-existent-email@paymentwall.com';
				}
				
				require_once('private/paymentwall/paymentwall.php');
				Paymentwall_Config::getInstance()->set(array(
				    'api_type' => Paymentwall_Config::API_GOODS,
				    'public_key' => ''.trim($PaymentWall['project_key']).'',
				    'private_key' => ''.trim($PaymentWall['secret_key']).''
				));
				
				/*
				$params = array(
					'key' => ''.trim($PaymentWall['project_key']).'',
					'country_code' => 'BR',
					'sign_version' => 3
				);
				ksort($params);
				$baseString = '';
				foreach ($params as $key => $value) {
					$baseString .= $key . '=' . ($value === false ? '0' : $value);
				}
				$baseString .= trim($PaymentWall['secret_key']);
				$sign = hash('sha256', $baseString);
				*/
				
				$widget = new Paymentwall_Widget(
				    ''.trim($donation[0]['account']).'',
				    ''.trim($PaymentWall['widget']).'',
				    array(
				        new Paymentwall_Product(
				        ''.trim($donation[0]['protocolo']).'',
				        trim($donation[0]['valor']),
				        ''.trim($PaymentWall['currency']).'',
				        ''.$donateDesc.'',
				        Paymentwall_Product::TYPE_FIXED
				        )
				    ),
				    array(
				        'email' => ''.$accEmail.'', 
				        'history[registration_date]' => ''.$donation[0]['data'].'',
				        'ps' => 'all',
				        'success_url' => (!empty($_SERVER['HTTPS']) ? 'https://' : 'http://').$panel_url.'?module=donate&page=order_pay&f='.trim($donation[0]['protocolo']).'&success'
				    )
				);
				
				$payUrl = trim($widget->getUrl());
				
				if(!empty($payUrl)) {
					
					$pay = "<iframe style='width:100%; height: 700px; border: 0;' src='".$payUrl."'></iframe>";
					
				} else {
					
					$pay = $LANG[39013]."<br /><br />Fail! Please, contact admin. #PAYBUTTON<br /><pre>".print_r($widget, true)."</pre>";
					
				}
			
				echo "
				<h1>".$LANG[10040]."</h1>
				<div class='pddInner'>
				    
					".$pay."
				    
				</div>
				";
				
			break;
			
			
			
			case 'flow':
			
				// Flow.cl
				
				$findAccEmail = DB::Executa("SELECT email FROM accounts WHERE login = '".trim($donation[0]['account'])."' LIMIT 1");
				if(count($findAccEmail) != 0) {
					$accEmail = $findAccEmail[0]['email'];
				}
				if(empty($accEmail)) {
					$accEmail = 'non-existent-email@flow.cl';
				}
				
				$params = array(
			        'commerceOrder' => trim($donation[0]['protocolo']), 
			        'subject' => $donateDesc, 
			        'currency' => trim($Flow['currency']), 
			        'amount' => $donation[0]['valor'], 
			        'email' => $accEmail, 
			        'paymentMethod' => 9, 
			        'urlConfirmation' => 'http://'.$panel_url.'/ipn/'.$Flow['ipn_url'], 
			        'urlReturn' => 'http://'.$panel_url,
			        'optional' => ''
		        );
				
				$url = 'https://www.flow.cl/api/payment/create';
				$params = array("apiKey" => trim($Flow['api_key'])) + $params;
				
				$keys = array_keys($params);
				sort($keys);
				
				$data = '';
				foreach ($keys as $key) {
					$data .= '&' . $key . '=' . $params[$key];
				}
				$data = substr($data, 1);
				
				if(!function_exists('hash_hmac')) {
					echo 'Function hash_hmac not exist!'; exit;
				}
				$sign = hash_hmac('sha256', $data, trim($Flow['secret_key']));
					
				try {
					
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_POST, TRUE);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data . '&s=' . $sign);
					$output = curl_exec($ch);

					if($output === false) {
						$error = curl_error($ch);
						throw new Exception($error, 1);
					}
					
					$info = curl_getinfo($ch);
					curl_close($ch);
					
					if(!in_array($info['http_code'], array('200', '400', '401'))) {
						throw new Exception('Unexpected error occurred. HTTP_CODE: '.$info['http_code'] , $info['http_code']);
					}
					
				} catch (Exception $e) {
					echo 'Error: ' . $e->getCode() . ' - ' . $e->getMessage();
				}
				
				$output = json_decode($output);
				
				if(!empty($output->code) && !empty($output->message)) {
					
					$payButton = "Fail! Please, contact admin. #PAYBUTTON<br /><pre>".print_r($output, true)."</pre>";
					
				} else {
					
					$payButton = "<a target='_blank' href='".trim($output->url)."?token=".trim($output->token)."' class='default big'>".$LANG[10042]."</a>";
					
				}
			
				echo "
				<h1>".$LANG[10040]."</h1>
				<div class='pddInner'>
				    ".$LANG[39013]."<br /><br />
				    
					".$payButton."
				    
				</div>
				";
				
			break;
			


			default:
			
				// g2apay
				
				$hash = hash('sha256', trim($donation[0]['protocolo']).trim($donation[0]['valor']).trim($G2APay['currency']).trim($G2APay['api_secret']));
				
				$post = 'api_hash='.trim($G2APay['api_hash']);
				$post .= '&hash='.$hash;
				$post .= '&order_id='.trim($donation[0]['protocolo']);
				$post .= '&amount='.trim($donation[0]['valor']);
				$post .= '&currency='.trim($G2APay['currency']);
				$post .= '&url_failure='.urlencode('https://'.$panel_url.'/?module=donate&page=order_pay&f='.trim($donation[0]['protocolo']));
				$post .= '&url_ok='.urlencode('https://'.$panel_url.'/?module=donate&page=order_pay&f='.trim($donation[0]['protocolo']));
				$post .= '&items=[{"sku":"99999","name":"'.trim($coinName).'","amount":"'.trim($donation[0]['valor']).'","qty":"'.trim($donation[0]['quant_coins']).'","price":'.round(trim(trim($donation[0]['valor'])/$donation[0]['quant_coins']), 2).',"id":"'.trim($donation[0]['protocolo']).'","url":"'.urlencode('https://'.$panel_url.'/?module=donate&page=add').'"}]';
				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://checkout.pay.g2a.com/index/createQuote');
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_TIMEOUT, 30);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
				
				$response = json_decode(curl_exec($ch));
				curl_close($ch);
				
				if(trim($response->status) == 'ok') {
					
					$payButton = "<a target='_blank' href='https://checkout.pay.g2a.com/index/gateway?token=".$response->token."' class='default big'>".$LANG[10042]."</a>";
					
				} else {
					
					$payButton = "Fail! Please, contact admin. #PAYBUTTON";
					
				}
			
				echo "
				<h1>".$LANG[10040]."</h1>
				<div class='pddInner'>
				    ".$LANG[39013]."<br /><br />
				    
					".$payButton."
				    
				</div>
				";
				
		}
		
	}

	if(isset($_GET['success']) && ($donation[0]['status'] == 3 || $donation[0]['status'] == 4)) {
		echo "<script type='text/javascript'>atualAlert('Pagamento recebido com sucesso!', 'OK')</script>";
	}

} else {
	
	echo $LANG[10046]."
	<div style='display:table;width:100%;'>
		<a href='./?module=donate&page=add' class='default' style='float:right;margin-right:30px'>".$LANG[10047]."</a>
	</div>
	";
}
