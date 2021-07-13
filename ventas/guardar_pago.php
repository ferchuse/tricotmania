<?php 
	include("../conexi.php");
	$link = Conectarse();
	
	// $id_usuarios = $_POST['id_usuarios'];
	// $id_turnos = $_COOKIE['id_turnos'];
	// $listaProductos = $_POST['productos'];
	// $articulos_ventas = $_POST['articulos_ventas'];
	// $ganancia_venta = 0;

	$insertarVentas = "UPDATE
	ventas
	SET
	
	estatus_ventas = '{$_POST["estatus_ventas"]}',
	forma_pago = '{$_POST["forma_pago"]}',
	tarjeta = '{$_POST["tarjeta"]}',
	efectivo = '{$_POST["efectivo"]}',
	pagocon_ventas = '{$_POST["pago"]}',
	cambio_ventas = '{$_POST["cambio"]}'
	
	WHERE id_ventas IN ({$_POST["id_ventas"]})
	";
	
	$respuesta["insertarVentas"] = $insertarVentas;
	$exec_query = mysqli_query($link,$insertarVentas);
	
	if($exec_query){
		$respuesta["estatus_venta"] = "success";
		$respuesta["mensaje_venta"] = "Venta Guardada";
		$respuesta["folio_venta"] = $_POST["id_ventas"];
		
		
		$respuesta["id_ventas"] = $_POST["id_ventas"];
	}
	else{
		$respuesta["estatus_venta"] = "error";
		$respuesta["mensaje_venta"] = "Error en Insertar: $insertarVentas  ".mysqli_error($link);	
		$respuesta["insertarVentas"] = $insertarVentas;
	}
	
	

	
	echo json_encode($respuesta);
?>