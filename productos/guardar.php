<?php
	header("Content-Type: application/json");
	include ("../conexi.php");
	$link = Conectarse();
	$respuesta = Array();
	
	
	$guardarProductos = "INSERT INTO productos SET 
	id_productos = '{$_POST["id_productos"]}',
	codigo_productos = '{$_POST["codigo_productos"]}',
	descripcion_productos = '{$_POST["descripcion_productos"]}',
	costo_proveedor = '{$_POST["costo_proveedor"]}',
	unidad_productos = '{$_POST["unidad_productos"]}',
	precio_menudeo = '{$_POST["precio_menudeo"]}',
	precio_mayoreo = '{$_POST["precio_mayoreo"]}',
	precio_dist = '{$_POST["precio_dist"]}',
	precio_fabrica = '{$_POST["precio_fabrica"]}',
	piezas_mayoreo = '{$_POST["piezas_mayoreo"]}',
	piezas_dist = '{$_POST["piezas_dist"]}',
	piezas_fabrica = '{$_POST["piezas_fabrica"]}',
	ganancia_menudeo_porc = '{$_POST["ganancia_menudeo_porc"]}',
	min_productos = '{$_POST["min_productos"]}',
	id_departamentos = '{$_POST["id_departamentos"]}',
	id_calidades = '{$_POST["id_calidades"]}',
	existencia_productos = '{$_POST["existencia_productos"]}'
	
	
	ON DUPLICATE KEY UPDATE 
	
	codigo_productos = '{$_POST["codigo_productos"]}',
	descripcion_productos = '{$_POST["descripcion_productos"]}',
	costo_proveedor = '{$_POST["costo_proveedor"]}',
	unidad_productos = '{$_POST["unidad_productos"]}',
	precio_menudeo = '{$_POST["precio_menudeo"]}',
	precio_mayoreo = '{$_POST["precio_mayoreo"]}',
	precio_dist = '{$_POST["precio_dist"]}',
	precio_fabrica = '{$_POST["precio_fabrica"]}',
	piezas_mayoreo = '{$_POST["piezas_mayoreo"]}',
	piezas_dist = '{$_POST["piezas_dist"]}',
	piezas_fabrica = '{$_POST["piezas_fabrica"]}',
	ganancia_menudeo_porc = '{$_POST["ganancia_menudeo_porc"]}',
	min_productos = '{$_POST["min_productos"]}',
	id_departamentos = '{$_POST["id_departamentos"]}',
	id_calidades = '{$_POST["id_calidades"]}',
	existencia_productos = '{$_POST["existencia_productos"]}'
	
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