<?php
	header("Content-Type: application/json");
	include("certificados.php");
	
	$certificado = new Certificados();
	$respuesta = array();
	$tipo = $_POST["tipo"];
	$url_certificado = urldecode($_POST["url_certificado"]);
	$url_certificado = substr($url_certificado,strpos($url_certificado, "../fileuploads")); // toma solo la ruta relativa del archivo
	
	//remover espacios y renombrar
	$new_file_name=str_replace(" ","_",$url_certificado);
	rename ($url_certificado, $new_file_name);
	$respuesta["url_certificado"] = $new_file_name ;
	
	if($tipo == "cer"){
		
			$respuesta["pem"] = $certificado->generaCerPem($new_file_name);
			$respuesta["datos_certificado"] = $certificado->validarCertificado($new_file_name.".pem");
			// $respuesta["pem"] = $certificado->generaCerPem(urlencode("../fileuploads/OOMA900301KX1 (1).cer"));
			// $respuesta["datos_certificado"] = $certificado->validarCertificado("../fileuploads/OOMA900301KX1 (1).cer.pem");
			// $respuesta["url_certificado"] = $url_certificado;
		
		
			//renombrar archivo con usando RFC y mover a ../facturacion/certificados
			$respuesta["renombrar_cer"]  =rename ($new_file_name, "../facturacion/certificados/".$respuesta["datos_certificado"]["rfc"].".cer" );
			$respuesta["renombrar_pem"] =	rename ($new_file_name.".pem", "../facturacion/certificados/".$respuesta["datos_certificado"]["rfc"].".cer.pem" );
	}
	else{
		//archivo .key
		$respuesta["rfc"] = $_POST["rfc"];
		$respuesta["pem"] = $certificado->generaKeyPem($new_file_name, $_POST["password"]);
		
		if($respuesta["pem"]["result"] == 1){
			$respuesta["renombrar_key"]  =rename ($new_file_name, "../facturacion/certificados/".$_POST["rfc"].".key" );
			$respuesta["renombrar_pem"] =	rename ($new_file_name.".pem", "../facturacion/certificados/".$_POST["rfc"].".key.pem");
		
		}
	}

	 // echo var_dump($certificado->generaCerPem("GUAF880601NA6.cer"));
	// echo var_dump($certificado->validarCertificado("GUAF880601NA6.cer.pem"));
	// echo var_dump($certificado->getFechaVigencia("GUAF880601NA6.cer.pem"));

	echo json_encode($respuesta);
	
	?>