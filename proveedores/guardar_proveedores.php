<?php
	header("Content-Type: application/json");
	include ("../conexi.php");
	$link = Conectarse();
	$respuesta = Array();
	
	$consulta = "INSERT INTO proveedores SET 
	id_proveedores = '{$_POST["id_proveedores"]}',
	nombre_proveedores = '{$_POST["nombre_proveedores"]}',
	telefono = '{$_POST["telefono"]}',
	dias_credito = '{$_POST["dias_credito"]}'
	
	
	ON DUPLICATE KEY UPDATE 
	
	id_proveedores = '{$_POST["id_proveedores"]}',
	nombre_proveedores = '{$_POST["nombre_proveedores"]}',
	telefono = '{$_POST["telefono"]}',
	dias_credito = '{$_POST["dias_credito"]}'
	
	
	
	";
	$result = mysqli_query($link, $consulta);
	
	$respuesta["consulta"] = $consulta;
	
	if($result){
		$respuesta["status"] = "success";
		$respuesta["mensaje"] = "Guardado";
		
	}	
	else{
		$respuesta["status"] = "error";
		$respuesta["mensaje"] = "Error $consulta  ".mysqli_error($link);		
	}
	
	echo json_encode($respuesta);
?>