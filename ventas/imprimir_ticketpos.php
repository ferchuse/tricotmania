<?php
	
	include('../conexi.php');
	include('../funciones/numero_a_letras.php');
	
	$link = Conectarse();
	$consulta = "SELECT * FROM ventas
	LEFT JOIN ventas_detalle USING (id_ventas)
	LEFT JOIN usuarios USING (id_usuarios)
	WHERE id_ventas={$_GET["id_ventas"]}";
	
	$result = mysqli_query($link, $consulta);
	
	while ($fila = mysqli_fetch_assoc($result)) {
		$fila_venta[] = $fila;
	}
	$respuesta = "";
	// for($t = 0 ; $t <= 1 ; $t++){
	
	
	$respuesta.=   "\x1b"."@";
	$respuesta.= "\x1b"."E".chr(1); // Bold
	$respuesta.= "!";
	$respuesta.=  "TRICOTMANIA\n";
	$respuesta.=  "\x1b"."E".chr(0); // Not Bold
	$respuesta.=  "\x1b"."@" .chr(10).chr(13);
	$respuesta.= "Folio:   ". $fila_venta[0]["id_ventas"]. "\n";
	$respuesta.= "Fecha:   " . date("d/m/Y", strtotime($fila_venta[0]["fecha_ventas"]))."\n";
	$respuesta.= "Hora:    " . date('H:i:s', strtotime($fila_venta[0]["hora_ventas"]))."\n";
	$respuesta.= "Cliente: " .$fila_venta[0]["nombre_cliente"]."\n";
	$respuesta.= "Vendedor: " .$fila_venta[0]["nombre_usuarios"]."\n\n";
	
	$respuesta.= "Cant   Descripcion       Importe  \n";
	
	
	foreach ($fila_venta as $i => $producto) { 
		$respuesta.=		number_format($producto["cantidad"], 0). "   ".$producto["descripcion"]
		."\n             $".$producto["precio"]."      $" . $producto["importe"].chr(10).chr(13) ;
		
	}
	$respuesta.="\n\n";
	if($fila_venta[0]["forma_pago"] == "efectivo"){
		$respuesta.="Total:     $ ". $producto["total_ventas"]."\n";
		$respuesta.="Pago Con:  $ ". $producto["pagocon_ventas"]."\n";
		$respuesta.="Cambio:    $ ". $producto["cambio_ventas"]."\n";
		
	}
	elseif($fila_venta[0]["forma_pago"] == "tarjeta"){
		
		$respuesta.="Subtotal:  $ ". $fila_venta[0]["subtotal_ventas"]."\n";
		$respuesta.="Comision:  $ ".$fila_venta[0]["comision"]."\n";
		$respuesta.="Total:     $ ".$fila_venta[0]["tarjeta"]."\n";
		
	}
	else{ 
		$respuesta.="Efectivo:  $ ". $producto["efectivo"]."\n";
		$respuesta.="Tarjeta:   $ ". $fila_venta[0]["tarjeta"]."\n";
		$respuesta.="Total:     $ ".  $fila_venta[0]["total_ventas"]."\n";
		
		
		
	}
	
	
	// $respuesta.= "\nTOTAL: $" .$fila_venta[0]["total_ventas"]."\n".chr(10).chr(13);
	$respuesta.= NumeroALetras::convertir($fila_venta[0]["total_ventas"], "pesos", "centavos").chr(10).chr(13).chr(10).chr(13);
	$respuesta.= "\nTEL: 54911478\n";
	$respuesta.= "GRACIAS POR SU COMPRA\n\n";
	$respuesta.= "\x1b"."d".chr(1); // Blank line
	// $respuesta.= "aSeguro de Viajero\n"; // Blank line
	$respuesta.= "\x1b"."d".chr(1). "\n"; // Blank line
	$respuesta.= "VA"; // Cut
	
	// }
	
	echo base64_encode ( $respuesta );
	exit(0);
	
?>

