<?php
header("Content-Type: application/json");
$respuesta=array();
$rfc = $_GET["rfc"];
$pass = $_GET["pass"];

error_reporting(0);

include "lib/cfdi32_multifacturas.php";
$error='OK';
if(function_exists('certificado_pem'))
{
		
		$cer= file_get_contents("certificados/".$rfc.".cer");
		$key= file_get_contents("certificados/".$rfc.".key");
		file_put_contents("certificados/".$rfc.".cer",$cer);
		file_put_contents("certificados/".$rfc.".key",$key);
		//unlink('tmp/GUAF880601NA6.cer.pem');
	 // unlink('tmp/GUAF880601NA6.key.pem');
		$datos['conf']['cer'] = "certificados/".$rfc.".cer.pem";
		$datos['conf']['key'] = "certificados/".$rfc.".key.pem";
		$datos['conf']['pass'] = $pass;
		certificado_pem($datos);
		if(file_exists("certificados/".$rfc.".cer.pem"))
		{
			
				$respuesta["cer"]["estatus"] = "success";
		}
		else
		{
				 $respuesta["cer"]["estatus"] = "error";
				// echo "<p>ERROR GENERANDO ARCHIVO .CER.PEM   <b>OK</b></p>";
				$error.='SI';
		}

		if(file_exists("certificados/".$rfc.".key.pem"))
		{
				$respuesta["key"]["estatus"] = "success";
			 // echo "<p>ARCHIVOS .KEY.PEM   <b>OK</b></p>";
		}
		else
		{
				$respuesta["key"]["estatus"] = "error";
				// echo "<p>ERROR GENERANDO ARCHIVO .KEY.PEM   <b>OK</b></p>";
				$error.='SI';
		}
		
}
else
{
		$error.='SI';
		$respuesta["all"]["estatus"] = "error";
		// ECHO "<p>ERROR : <b>FALTA el archivo cfdi32_multifacturas.php para realizar la ultima prueba</b></p>";
}

if($error!='OK')
{
		 $respuesta["all"]["estatus"] = "error";
		 $respuesta["all"]["error"] = "$error";
		// echo "<h1>ERROR GRAVE, NO SE PUEDEN PROCESAR LOS CERTIFICADOS</h1>";
}


echo json_encode($respuesta);
?>