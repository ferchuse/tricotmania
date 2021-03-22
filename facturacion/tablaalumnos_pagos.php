<?php
	include("login/login_success.php");
	include_once("conexi.php");
	$link= Conectarse();
	$menu_activo = "reportes";
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Historial de Pagos</title>
	<?php include("styles.php")?>
</head>
<body>
	<div class="container-fluid">
		<?php include("menu.php");?>
	</div>
	
	<div class="container">
		<h4 class="text-center">Historial de Pagos por alumno</h4>
		<hr>
		
		
			<div class="row">
				<div class="col-sm-12">
					<form id="form_pagos" class="form-inline">
						<input  class="form-control hidden" type="text" name="id_alumnos" id="id_alumnos">
							
						<div class="form-group  hidden-print">
							<label for="nombre_alumnos">Nombre del Alumno:</label>
							<input required class="form-control" type="text" name="nombre_alumnos" id="nombre_alumnos">
						</div>						
						
							<button class="btn btn-success hidden-print" id="btn_balumno">
								<i class="fa fa-search"></i> Buscar
							</button>
							<span class="pull-right">
								<button class="btn btn-info  hidden-print" type="button" id="btn_exel"><i class="fa fa-file-excel-o"></i> Exportar a Exel</button>
								<button class="btn btn-default  hidden-print" type="button" id="btn_imprimir"><i class="fa fa-print"></i> Imprimir</button>
							</span>
					</form>
				</div>
			</div>
	
		<hr  class="hidden-print">
		<div class="row">
			<div class="col-sm-12" id="lista_p">
					<?php
				if(isset($_GET['id_alumnos'])){
				$consulta = "SELECT
					*
				FROM
					pagos
					LEFT JOIN costos USING (id_costos)
					LEFT JOIN ciclo_escolar USING (id_ciclos) 
				WHERE
					id_alumnos = ".$_GET['id_alumnos'];

				$meses = array(
				0 => "ENERO",
				1 => "FEBRERO",
				2 => "MARZO",
				3 => "ABRIL",
				4 => "MAYO",
				5 => "JUNIO",
				6 => "JULIO",
				7 => "AGOSTO",
				8 => "SEPTIEMBRE",
				9 => "OCTUBRE",
				10 => "NOVIEMBRE",
				11 => "DICIEMBRE"
				);

				$mensaje_error = 'no encontrado';

				$result_complete = mysqli_query($link, $consulta)
				or die ("Error al ejecutar consulta: $consulta".mysqli_error($link));

				$numero_filas = mysqli_num_rows($result_complete);
				$contador = 0;

				$tabla_pagos = array();


				?>
				<table class="table table-bordered" id="exportar_exel">
						<thead>
								<tr>
									<th>Fecha Pago</th>
									<th>Concepto</th>
									<th>Descuento</th>
									<th>Recargos</th>
									<th>Total Pagado</th>
									<th class="hidden-print">Acciones</th>
								</tr>
						</thead>
						<tbody>
							<?php
							while($fila = mysqli_fetch_assoc($result_complete)){
								$contador++;
								$id_pagos = $fila['id_pagos'];
								$nombre_ciclos  = $fila['nombre_ciclos'];
								$fecha_pagos  = date("d/m/Y", strtotime($fila['fecha_pagos']));
								$meses_pagos  = $fila['meses_pagos'];
								$descripcion_pagos  = $fila['descripcion_pagos'];
								$descuento_pagos  = $fila['descuento_pagos'];
								$recargos_pagos  = $fila['recargo_pagos'];
								$total_pagos  = $fila['total_pagos'];
								$es_articulo = $fila["es_articulo"];
								//$tabla_pagos[$id_ciclos][$meses_pagos][] =$total_pagos  ;
								$descripcion = array();
								
								if($fila['es_articulo'] == "1"){
									$descripcion[] = $fila['descripcion_pagos'];
								}else{
									$consultaDetalle = "SELECT * FROM pagos_detalle WHERE id_pagos='$id_pagos'";
									$resultDetalle = mysqli_query($link, $consultaDetalle);
									while($rowDetalle = mysqli_fetch_assoc($resultDetalle)){
								
									$descripcion[] = $rowDetalle['descripcion_pagos'];
									
									}
								}
								?>
							
								<tr>
									<td><?php echo $fecha_pagos;?></td>
									<td><?php echo implode(",",$descripcion);?></td>
									<td><?php echo $descuento_pagos;?></td>
									<td><?php echo $recargos_pagos;?></td>
									<td class="text-right"> $ <?php echo number_format($total_pagos);?></td>
									<td class="text-center hidden-print">
										<a  title="Reimpresión pago" class="btn btn-info btn_reimprecionPago  hidden-print" href="imprimir_pago.php?folio_pago=<?php echo $id_pagos;?>"><i class="fa fa-print"></i></a> 
									</td>
								</tr>
							<?php
							}
							?>
						</tbody>
				</table>
				<?php
				}
				?>
			
			</div>
		</div>
	</div>
		
		<?php include('scripts.php');?>
		<script src="js/tablaalumnos_pagos.js"></script>
		
</body>
</html>