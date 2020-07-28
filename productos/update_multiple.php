<?php
	header("Content-Type: application/json");
	include ("../conexi.php");
	$link = Conectarse();
	$respuesta = Array();
	
	
	$guardarProductos = "UPDATE 
	productos
	SET
	precio_menudeo = '{$_POST["precio_menudeo"]}',
	precio_mayoreo = '{$_POST["precio_mayoreo"]}',
	precio_dist = '{$_POST["precio_dist"]}',
	precio_fabrica = '{$_POST["precio_fabrica"]}',
	piezas_mayoreo = '{$_POST["piezas_mayoreo"]}',
	piezas_dist = '{$_POST["piezas_dist"]}',
	piezas_fabrica = '{$_POST["piezas_fabrica"]}',

	min_productos = '{$_POST["min_productos"]}',
	id_departamentos = '{$_POST["id_departamentos"]}',
	id_calidades = '{$_POST["id_calidades"]}'
	
	WHERE id_productos IN ({$_POST["productos_seleccionados"]})
	;
	
	";
	if(mysqli_query($link,$guardarProductos)){
		$respuesta['estatus'] = "success";
		$id_producto = mysqli_insert_id($link);
		}else{
		$respuesta['estatus'] = "error";
		$respuesta['mensaje'] = "Error en ".$guardarProductos.mysqli_error($link);
	}
	
	
	
	echo json_encode($respuesta);
?>