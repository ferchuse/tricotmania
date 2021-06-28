<?php
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	
	require_once(__DIR__ . '/../../lib/sendinblue/vendor/autoload.php');
	require_once(__DIR__ . '/../../conexi.php');
	
	$link = Conectarse();
	
	
	// $api_key = file_get_contents("../../lib/sendinblue/keys.txt");
	
	//busca api key
	$consulta_emisor	= "SELECT * FROM emisores
	WHERE id_emisores = '1'";
	
	$result = mysqli_query($link, $consulta_emisor);
	
	if($result && mysqli_num_rows($result)){
		$respuesta["consulta_facturas_estatus"] = "success";
		while($fila = mysqli_fetch_assoc($result)){
			$emisor = $fila;
		}
		
	}
	else{
		
		echo mysqli_error($link);
		echo $consulta_facturas;
	}
	
	$api_key = $emisor["api_sendinblue"];
	
	// Configure API key authorization: api-key
	$config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', $api_key);
	
	$apiInstance = new SendinBlue\Client\Api\TransactionalEmailsApi(
	
    new GuzzleHttp\Client(),
    $config
	);
	$sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail(); // \SendinBlue\Client\Model\SendSmtpEmail | Values to send a transactional email
	
	$contactos = array();
	$lista_correos = explode("," ,$_GET["correo"] ) ;
	
	foreach($lista_correos as $index => $correo){
		$contactos[] = array('email'=>strtolower(trim($correo)), 'name'=>$_GET["nombre"]);
	}
	
	$sendSmtpEmail['to'] = $contactos;
	
	$sendSmtpEmail['templateId'] = 1;
	$sendSmtpEmail['params'] = array(
	'folio'=> $_GET["folio"],
	'url_pdf'=> "www.tricotmania.com/facturacion/facturacion/". $_GET["url_pdf"],
	'url_xml'=> "www.tricotmania.com/facturacion/facturacion/". $_GET["url_xml"]
	);
	
	// $adjunto =  __DIR__ .'/'. $_GET["url_pdf"];
	// $adjunto = "https://www.pakmailejercito.com.mx/sistema/facturacion/facturacion/". $_GET["url_pdf"];
	// print_r($adjunto);
	// print_r("<br>");
	// $pdfdocPath = __DIR__ .'/'. $_GET["url_pdf"];
	// print_r($pdfdocPath);
	// $b64Doc = chunk_split(base64_encode(file_get_contents($pdfdocPath)));
	
	// $attachement = new \SendinBlue\Client\Model\SendSmtpEmailAttachment();
    // $attachement['url']= $adjunto;
    // $attachement['name']= $_GET["url_pdf"];
    // $attachement['content']= $b64Doc ;
    // $sendSmtpEmail['attachment']= $attachement;
    // $sendSmtpEmail['headers'] = array('Content-Type'=>'application/pdf','Content-Disposition'=>'attachment','filename'=>$_GET["url_pdf"],"charset"=>"utf-8");
	
	
	// $sendSmtpEmail['headers'] = array('X-Mailin-custom'=>'custom_header_1:custom_value_1|custom_header_2:custom_value_2');
	// echo json_encode($attachement);
	try {
		$result = $apiInstance->sendTransacEmail($sendSmtpEmail);
		print_r($result);
		echo json_encode($result, true);
		
		} catch (Exception $e) {
		echo 'Exception when calling TransactionalEmailsApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
	}
	
	exit();
	/*
		
		// PHP SDK: https://github.com/sendinblue/APIv3-php-library
		require_once(__DIR__ . '/vendor/autoload.php');
		
		// Configure API key authorization: api-key
		$config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', 'YOUR_API_KEY');
		
		$apiInstance = new SendinBlue\Client\Api\ContactsApi(
		// If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
		// This is optional, `GuzzleHttp\Client` will be used as default.
		new GuzzleHttp\Client(),
		$config
		);
		$createContact = new \SendinBlue\Client\Model\CreateContact(); // \SendinBlue\Client\Model\CreateContact | Values to create a contact
		$createContact['email'] = 'john@doe.com';
		
		try {
		$result = $apiInstance->createContact($createContact);
		print_r($result);
		} catch (Exception $e) {
		echo 'Exception when calling ContactsApi->createContact: ', $e->getMessage(), PHP_EOL;
		}
		
	*/
?>