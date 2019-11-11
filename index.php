<?php
	include("login/login_success.php");
	include("conexi.php");
	include("funciones/generar_select.php");
	$link = Conectarse();
	$menu_activo = "principal";
	error_reporting(0);
	
	$consulta = "SELECT * FROM productos";
	$result = mysqli_query($link,$consulta);
	$num_rows = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
		
		<style>
			<style>
				.tabla_totales .row{
		  	margin-bottom: 10px;
				}
				
				.tab-pane {
				display: block;
				overflow: auto;
				overflow-x: hidden;
				height: 350px;
				width: 100%;
				padding: 10px;				
				}			
			</style>  
		</style>
		
    <title>Nueva Venta</title>
    <?php include("styles.php");?>
	</head>
  <body>
		<div class="container-fluid hidden-print">
			<?php include("menu.php");?>
		</div>
		<div class="container-fluid hidden-print">
			
			<div class="row">
				<form id="form_agregar_producto" class="form-inline" autocomplete="off">
					<div class="col-md-4">
						<label for="">Código:</label>
						
						<input id="codigo_producto"   type="search" class="form-control" placeholder="ESC" size="50">
						
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="">Descripción:</label>
							<input id="buscar_producto"  autofocus type="search" class="form-control" size="50"  placeholder="F10">
						</div>
					</div>
				</form>
				<div class="col-md-4 ">
					<div class="form-group">
						<label>Vendedor</label>
						<?= generar_select($link, "usuarios", "id_usuarios", "nombre_usuarios");?>
					</div>
				</div>
			</div>
			
			
			<div class="row">
				<div class="col-md-12">
					<div class="tab-pane">
						<table id="tabla_venta" class="table table-bordered table-condensed">
							<thead class="bg-success">
								<tr>
								<th class="text-center">Cantidad</th>
								<th class="text-center">Unidad</th>
								<th class="text-center">Descripcion del Producto</th>
								<th class="text-center">Precio Unitario</th>
								<th class="text-center">Importe</th>
								<th class="text-center">Existencia</th>
								<th class="text-center">Acciones</th>
								</tr>
							</thead>
							<tbody >
								
							</tbody>
						</table>
					</div>
				</div>
			</div>
			
			<br>
			<section id="footer">
				<div class="row">
					<div class="col-sm-9 text-right">
						<button class="btn btn-info btn-lg"  id="nueva_venta" onclick="window.location.reload(true);">
							Nueva Venta
						</button>
						<button class="btn btn-success btn-lg" FORM="" id="cerrar_venta">F12 - Cobrar</button>
					</div>
					<div class="col-sm-1 h2">
						<strong>TOTAL:</strong>
					</div>
					<div class="col-sm-2 h1">
						<input readonly id="total" type="text" class="form-control input-lg text-right " value="0" name="total">
					</div>
				</div>
				
			</section>
			
		</div>
		
		<div id="ticket" class="visible-print">
			
		</div>
		<?php  include('scripts.php'); ?>
		<?php include('forms/modal_venta.php'); ?>
		<?php include('forms/modal_granel.php'); ?>
		<script src="js/nueva_venta.js"></script>
		
	</body>
</html>				