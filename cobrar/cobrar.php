<?php
	include("../login/login_success.php");
	include("../funciones/generar_select.php");
	include("../conexi.php");
	$link = Conectarse();
	$menu_activo = "cobrar";
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
	else 	
	{
		$tipo_corte = "turno";
	}
	
	$tipo_corte = "dia";
	
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
		
		$consulta_egresos = "SELECT * FROM egresos
		LEFT JOIN catalogo_egresos USING(id_catalogo_egresos) 
		LEFT JOIN proveedores USING(id_proveedores) 
		WHERE fecha_egresos =  '$fecha_corte'";
		
		
		$consulta_ingresos= "SELECT * FROM ingresos WHERE fecha_ingresos =  '$fecha_corte'";
		
		if(isset($_GET["id_usuarios"]) && $_GET["id_usuarios"] != ''){
			$consulta_totales = "SELECT * FROM
			
			(SELECT SUM(cantidad_ingresos) AS entradas FROM ingresos WHERE estatus_ingresos='ACTIVO' AND fecha_ingresos = '$fecha_corte' ) AS tabla_entradas,
			#(SELECT SUM(cantidad_egresos) AS salidas FROM egresos WHERE estatus_egresos='ACTIVO' AND fecha_egresos = '$fecha_corte'  ) AS tabla_salidas,
			(SELECT COUNT(id_ventas) AS ventas_totales FROM ventas WHERE estatus_ventas='PAGADO' AND fecha_ventas = '$fecha_corte' AND id_usuarios = {$_GET["id_usuarios"]}) AS tabla_ventas,
			(SELECT SUM(total_ventas) AS importe_ventas FROM ventas WHERE estatus_ventas='PAGADO' AND fecha_ventas = '$fecha_corte' AND id_usuarios = {$_GET["id_usuarios"]}) AS tabla_importe
			";
			
			$consulta_ventas = "SELECT * FROM ventas LEFT JOIN usuarios USING(id_usuarios) 
			WHERE fecha_ventas = '$fecha_corte' 
			AND id_usuarios = {$_GET["id_usuarios"]}
			
			ORDER BY id_ventas DESC
			";
		}
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
		
		$consulta_egresos = "SELECT * FROM egresos 
		LEFT JOIN catalogo_egresos USING(id_catalogo_egresos) 
		LEFT JOIN proveedores USING(id_proveedores) 
		WHERE id_turnos = '{$_COOKIE["id_turnos"]}' 
		ORDER BY hora_egresos";
		
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
	
	
	$consulta_productos = "SELECT
	SUM(cantidad) as cantidad,
	descripcion,
	SUM(importe) as importe
	FROM
	ventas
	LEFT JOIN ventas_detalle USING (id_ventas)
	WHERE
	fecha_ventas = '$fecha_corte'
	AND estatus_ventas <> 'CANCELADO'
	GROUP BY id_productos
	ORDER BY importe DESC
	";
	
	$result = mysqli_query($link, $consulta_productos);
	
	while ($fila = mysqli_fetch_assoc($result)) {
		$productos_vendidos[] = $fila;
	}
?>
<!DOCTYPE html>
<html lang="es">
	
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Cobrar Tickets</title>
		
		<link href="../css/bootstrap_3.4.1.css" rel="stylesheet" media="all" />
		<link href="../css/alertify.min.css" rel="stylesheet" media="all" />
		<link href="../css/all.min.css" rel="stylesheet">
		<link href="../css/imprimir_pago.css" rel="stylesheet" media="all">
		<link href="../css/imprimir_venta.css" rel="stylesheet" >
		<link href="../css/menu.css" rel="stylesheet" >
		<link href="../css/b4-margin-padding.css" rel="stylesheet" >
		
	</head>
	
	<body>
		<pre hidden>
			<?php
				echo $consulta_totales;
				echo $consulta_ventas;
				echo var_dump($totales); 
			?>	
		</pre>
		<?php include("../menu_carpetas.php"); ?>
		
		<div class="container-fluid hidden-print">
			<div class="row">
				
				<h4 class="text-center">
					Cobrar Tickets 
				</h4>
				<div class="col-md-2 col-sm-offset-3 m-auto mt-3 text-center">
					
					<div class="form-group">
						<label>1 Ticket: </label>
						<input type="number" class="form-control"  name="id_ventas" id="buscar_venta" autofocus >
					</div>
					
				</div>
				<div class="col-md-2 col-sm-offset-2 m-auto mt-3 text-center">
					
					<div class="form-group">
						<label>Varios Tickets: </label>
						<div class="input-group">
							<input type="text" class="form-control"  name="id_ventas" id="folios_multiples"  >
							<span class="input-group-btn">
								<button id="btn_cobrar_varios" class="btn btn-primary" type="button">
								<i class="fas fa-search" ></i> Cobrar 
								</button>
							</span>
						</div>
						
					</div>
				</div>
				
			</div>
			<hr>
		</div>
	</div>
</form>





<div id="Pago" class="visible-print">
</div>


<div id="ver_venta">
</div>
<div id="arqueo" class="ticket visible-print">
</div>


<?php include('modal_cobrar.php'); ?>

<?php include('../scripts_carpetas.php'); ?>



<script src="../lib/pos_print/websocket-printer.js" > </script>
<script src="js/resumen.js?v=<?= date("d-m-Y-H-i-s")?>"></script>



</body>

</html>