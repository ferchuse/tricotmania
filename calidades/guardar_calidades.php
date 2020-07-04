<?php
	header("Content-Type: application/json");
	include ('../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	
	
	
	$query = "
	INSERT INTO calidades 	SET
	id_calidades = '{$_POST["id_calidades"]}',
	calidad = '{$_POST["calidad"]}'
	
	ON DUPLICATE KEY UPDATE 
	
	id_calidades = '{$_POST["id_calidades"]}',
	calidad = '{$_POST["calidad"]}'
	
	";
	
	
	$respuesta["consulta"] = $query;
	
	$result = mysqli_query($link, $query);
	
	if($result){
		$respuesta["estatus"] = "success";
		$respuesta["mensaje"] = "Se guardó correctamente";
	}
	else{ 
		$respuesta["estatus"] = "error";
		$respuesta["mensaje"] = mysqli_error($link);		
	}
	echo json_encode($respuesta);
?>