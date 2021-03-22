<?php header("Content-Type: application/json"); 
	include("../conexi.php"); $link = Conectarse();
	$respuesta = array();
	$host="http://panel.facturacionmexico.com.mx/panel//api.php"; 
	$query["accion"]='nuevo_cliente'; 
	$query["APIU"]="2d831e7c6c7e8640e91842fdafe3cbbf";
	$query["APIP"]="6a6610feab86a1f294dbbf5855c74af9"; 
	
	$query["RFC"]=$_POST["rfc_emisores"]; 
	$query["RFC_PASS"] =$_POST["password"];
	$query["NOMBRE_CLIENTE"]=$_POST["razon_social_emisores"]; 
	$query["NOMBRE_EMPRESA"]=$_POST["razon_social_emisores"]; 
	//agregar ​ ​los ​ ​parámetros ​ ​a ​ ​utilizar 
	//$query="accion=$accion&APIU=$APIU&APIP=$APIP&RFC=$RFC&RFC_PASS=$RFC_PASS&NOMBRE_CLIENTE=$NOMBRE_CLIENTE&NOMBRE_EMPRESA=$NOMBRE_EMPRESA ​";
	
	$respuesta["api_respuesta"] = post_data($query);
	
		
		function post_data($data){
			$url = "http://panel.facturacionmexico.com.mx/panel//api.php";
			
			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL, $url); // Establecer URL
			curl_setopt($ch, CURLOPT_POST, true); // Usar metodo POST
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //  
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)) ; // POST DATA

			$result["mensaje_api"] = curl_exec($ch);
			if($result["mensaje_api"] === FALSE){
				$result["estatus_curl"] = "error";
				$result["mensaje_curl"] = 'cURL falló: '. curl_error($ch);
			}
			curl_close($ch);
			return $result;
		}
		
		
//$accion ​= ​'agregar_saldo'; 
$insert = "INSERT INTO emisores 
SET rfc_emisores='".$_POST["rfc_emisores"]."', 
razon_social_emisores='".$_POST["razon_social_emisores"]."', 
regimen_emisores='".$_POST["regimen_emisores"]."', 
password='".$_POST["password"]."', 
correo_emisores='".$_POST["correo_emisores"]."', 
lugar_expedicion_emisores= '".$_POST["lugar_expedicion_emisores"]."'"; 

if(mysqli_query($link,$insert)){
	$respuesta['estatus'] = "success"; 
	
}
else{ 

	$respuesta['estatus'] = "error"; 
	
	$respuesta['mensaje'] = "Error en ".mysqli_error($link); 
	
} 
	
echo json_encode($respuesta); ?>