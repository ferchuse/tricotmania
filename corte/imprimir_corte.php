<?php
	include("../login/login_success.php");
	include("../funciones/generar_select.php");
	include("../conexi.php");
	include('../funciones/numero_a_letras.php');
	$link = Conectarse();
	$menu_activo = "resumen";
	$egresos = 0;
	$totales = [];
	
	if (isset($_GET["id_turnos"])) {
		$_COOKIE["id_turnos"] = $_GET["id_turnos"];
	}
	if (isset($_GET["fecha_ventas"])) {
		$fecha_corte = $_GET["fecha_ventas"];
		} else {
		$fecha_corte = date("Y-m-d");
	}
	if (isset($_GET["tipo_corte"])) {
		$tipo_corte = $_GET["tipo_corte"];
	} 
	else 	{
		$tipo_corte = "turno";
	}
	
	$consulta_turno = "SELECT * FROM turnos WHERE cerrado='0'";
	$result_turno = mysqli_query($link, $consulta_turno);
	while ($fila = mysqli_fetch_assoc($result_turno)) {
		$fila_turno = $fila;
	}
	
	
	
	if ($tipo_corte == "dia") {
		//Corte por dia
		$consulta_ventas = "SELECT * FROM ventas LEFT JOIN usuarios USING(id_usuarios) 
		WHERE fecha_ventas = '$fecha_corte' ORDER BY id_ventas DESC
		";
		
		
		$consulta_totales = "SELECT * FROM
		
		(SELECT SUM(cantidad_ingresos) AS entradas FROM ingresos WHERE estatus_ingresos='ACTIVO' AND fecha_ingresos = '$fecha_corte') AS tabla_entradas,
		(SELECT SUM(cantidad_egresos) AS salidas FROM egresos WHERE estatus_egresos='ACTIVO' AND fecha_egresos = '$fecha_corte') AS tabla_salidas,
		(SELECT COUNT(id_ventas) AS ventas_totales FROM ventas WHERE estatus_ventas='PAGADO' AND fecha_ventas = '$fecha_corte') AS tabla_ventas,
		(SELECT SUM(total_ventas) AS importe_ventas FROM ventas WHERE estatus_ventas='PAGADO' AND fecha_ventas = '$fecha_corte') AS tabla_importe
		";
		
		$consulta_egresos = "SELECT * FROM egresos LEFT JOIN catalogo_egresos USING(id_catalogo_egresos) WHERE fecha_egresos =  '$fecha_corte'";
		$consulta_ingresos= "SELECT * FROM ingresos WHERE fecha_ingresos =  '$fecha_corte'";
	} 
	else {
		
		//Corte por turno
		$consulta_ventas = "SELECT * FROM ventas LEFT JOIN usuarios USING(id_usuarios) 
		WHERE id_turnos = '{$_COOKIE["id_turnos"]}' ORDER BY id_ventas DESC
		";
		$consulta_totales = "SELECT * FROM
		
		(SELECT SUM(cantidad_ingresos) AS entradas FROM ingresos WHERE estatus_ingresos='ACTIVO' AND id_turnos = '{$_COOKIE["id_turnos"]}') AS tabla_entradas,
		(SELECT SUM(cantidad_egresos) AS salidas FROM egresos WHERE estatus_egresos='ACTIVO'  AND id_turnos = '{$_COOKIE["id_turnos"]}') AS tabla_salidas,
		(SELECT COUNT(id_ventas) AS ventas_totales FROM ventas WHERE estatus_ventas='PAGADO' AND id_turnos = '{$_COOKIE["id_turnos"]}') AS tabla_ventas,
		(SELECT SUM(total_ventas) AS importe_ventas FROM ventas WHERE estatus_ventas='PAGADO' AND id_turnos = '{$_COOKIE["id_turnos"]}') AS tabla_importe
		";
		
		$consulta_egresos = "SELECT * FROM egresos LEFT JOIN catalogo_egresos USING(id_catalogo_egresos) WHERE id_turnos = '{$_COOKIE["id_turnos"]}' ORDER BY hora_egresos";
		
		$consulta_ingresos= "SELECT * FROM ingresos WHERE id_turnos =  '{$_COOKIE["id_turnos"]}'";
	}
	
	
	$resultadoVentas = mysqli_query($link, $consulta_ventas);
	$resultado_totales = mysqli_query($link, $consulta_totales) or die(mysqli_error($link));
	$resultado_egresos = mysqli_query($link, $consulta_egresos) or die(mysqli_error($link));
	$result_ingresos = mysqli_query($link, $consulta_ingresos) or die(mysqli_error($link));
	
	while ($fila = mysqli_fetch_assoc($resultado_totales)) {
		$totales = $fila;
	}
	while ($fila = mysqli_fetch_assoc($resultado_egresos)) {
		$lista_egresos[] = $fila;
	}
	
	while ($fila = mysqli_fetch_assoc($result_ingresos)) {
		$lista_ingresos[] = $fila;
	}
	
	
	
	$respuesta = "";
	
	$respuesta.=   "\x1b"."@";
	$respuesta.= "\x1b"."E".chr(1); // Bold
	$respuesta.= "!";
	$respuesta.=  "TRICOTMANIA\n";
	$respuesta.=  "\x1b"."E".chr(0); // Not Bold
	$respuesta.=  "\x1b"."@" .chr(10).chr(13);
	$respuesta.= "Resumen del Dia:      ". date("d/m/Y"). "\n";
	$respuesta.= "Hora:                 " .date("H:i:s")."\n";
	$respuesta.= "Usuario:              " . $_COOKIE["nombre_usuarios"]."\n";
	$respuesta.= "Inicio Turno:         " .date("H:i:s", strtotime($hora_inicios))."\n";
	$respuesta.= "Fin Turno:            " .date("H:i:s", strtotime($hora_fin))."\n";
	$respuesta.= "Numero de Ventas:     " .$totales["ventas_totales"]."\n\n";
	$respuesta.= "Fondo de Caja:        " .number_format($_COOKIE["efectivo_inicial"], 2)."\n";
	$respuesta.= "Ventas en Efectivo: +$" .number_format($ , 2)."\n";
	$respuesta.= "Ventas con Tarjeta: +$" .number_format($suma_tarjeta, 2)."\n";
	$respuesta.= "Entradas:           +$" .number_format($totales["entradas"], 2)."\n";
	$respuesta.= "Salidas:            -$" .number_format($totales["salidas"], 2)."\n";
	$respuesta.= "__________________________\n";
	$respuesta.= "Saldo Final:         $" .number_format($saldo_final, 2)."\n";
	
	
	
	$respuesta.= NumeroALetras::convertir($fila_venta[0]["total_ventas"], "pesos", "centavos").chr(10).chr(13).chr(10).chr(13);
	$respuesta.= "GRACIAS POR SU COMPRA";
	$respuesta.= "\x1b"."d".chr(1); // Blank line
	// $respuesta.= "aSeguro de Viajero\n"; // Blank line
	$respuesta.= "\x1b"."d".chr(1). "\n"; // Blank line
	$respuesta.= "VA"; // Cut
	
	// }
	
	echo base64_encode ( $respuesta);
	exit(0);
	
?>

