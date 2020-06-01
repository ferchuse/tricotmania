<?php
	header("Content-Type: application/json");
	include ('../conexi.php');
	$link = Conectarse();
	$tabla = $_POST["tabla"];
	$id_registro = $_POST["id_campo"];
	$name = $_POST["name"];
	$id_field = 'id_'.$tabla;
	$name_field = 'nombre_'.$tabla;
	$respuesta = array();
	

	switch($tabla){
		
		case "departamentos":
		
		$query = "INSERT INTO $tabla ($id_field, $name_field, piezas_descuento, porc_descuento) 
    VALUES('$id_registro', '$name','{$_POST["piezas_descuento"]}', '{$_POST["porc_descuento"]}')
    ON DUPLICATE KEY UPDATE 
		
		$name_field = '$name',
		piezas_descuento = '{$_POST["piezas_descuento"]}',
		porc_descuento = '{$_POST["porc_descuento"]}'
		
		";
		break;
		
		default:
		$query = "INSERT INTO $tabla ($id_field, $name_field) 
    VALUES('$id_registro', '$name')
    ON DUPLICATE KEY UPDATE $name_field = '$name'";
		break;
		
	}
	
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