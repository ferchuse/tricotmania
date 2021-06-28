<?php 
	include('../conexi.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	
	
	$denominaciones = ["1000", "500", "200", "100", "50", "20", "10", "5", "2", "1", "0.5", "0.2", "0.1"];
	$consulta = "SELECT * FROM arqueo 
	
	LEFT JOIN usuarios USING(id_usuarios)
	WHERE id_arqueo= '{$_GET['id_registro']}'";
	
	
	$result = mysqli_query($link,$consulta);
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			
			die("<div class='alert alert-danger'>Registro No encontrado</div>");
			
			
		}
		
		while($fila = mysqli_fetch_assoc($result)){
			
			$filas = $fila ;
			
		}
		
		
		
		$respuesta = "";
		
		$respuesta.=   "\x1b"."@";
		$respuesta.= "\x1b"."E".chr(1); // Bold
		$respuesta.= "        Arqueo    \n";
		$respuesta.= "Fecha:     ".date("d/m/Y", strtotime($filas["fecha_arqueo"]))."\n";
		$respuesta.= "Hora:      ".$filas["hora_arqueo"]."\n";
		$respuesta.= "Usuario:  " . $_COOKIE["nombre_usuarios"]."\n";
		$respuesta.= "Denom    Cantidad       Importe \n";
		foreach($denominaciones as $i => $denominacion){
			$respuesta.= "$".str_pad($denominacion, 10)." ". str_pad(number_format($filas[$denominacion]), 10, " ", STR_PAD_BOTH ). "  $" .str_pad(number_format($filas[$denominacion] * $denominacion),8," ", STR_PAD_LEFT )."\n" ;
			
			
		}
		
		$respuesta.= "\nSUBTOTAL           		 $". number_format($filas["subtotal"])."\n";
		$respuesta.= "\nFONDO DE CAJA           - $". number_format($filas["fondo_caja"])."\n";
		$respuesta.= "\nIMPORTE TOTAL           $". number_format($filas["importe"])."\n";
		
		// $respuesta.= NumeroALetras::convertir($fila_venta[0]["total_ventas"], "pesos", "centavos").chr(10).chr(13).chr(10).chr(13);
		// $respuesta.= "GRACIAS POR SU COMPRA";
		$respuesta.= "\x1b"."d".chr(1); // Blank line
		
		$respuesta.= "\x1b"."d".chr(1). "\n"; // Blank line
		$respuesta.= "VA"; // Cut
		
		
		
		echo base64_encode ( $respuesta);
		
		
	}
	else {
		echo "Error en ".$consulta.mysqli_Error($link);
		
	}
	
	
?>	