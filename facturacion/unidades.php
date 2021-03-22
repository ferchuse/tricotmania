<?php
	include("login/login_success.php");
	include_once("control/is_selected.php");
	include_once("conexi.php");
	$link = Conectarse();
	$menu_activo = "facturas";
	
	$year = date("Y");
	$mes = date("n");
	
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catálogo de Unidades</title>

	<?php include("styles.php");?>
	
  </head>
  <body>

  <div class="container-fluid">
		<?php include("menu.php");?>
	</div>
	
	<h3 class="text-center">Catálogo de Unidades</h3>
	
	<div class="container"  > 
		
		<form class=" hidden-print" id="form_filtros">
			<div class="form-group">
				<label for="id_ciclos" class="text-center">Unidad:</label>
				<input type="text" id="buscar_unidades" class="form-control" placeholder="Buscar por nombre o descripción">
			</div>
		</form>
	</div>
	<hr>
	<div class="container"  > 
		<div class="row">
			<div class="col-sm-12" >
				<div class="panel panel-primary" >
					<div class="panel-heading hidden-print" >
						<h4>Lista de Unidades Activas 
							<span class="pull-right hidden-print">
								<button class="btn btn-success exportar" href="facturas_nueva.php">
										<i class="fa fa-arrow-right" ></i> Exportar
								</button>	
								<button class="btn btn-info" onclick="window.print()">
										<i class="fa fa-print" ></i> Imprimir
								</button>	
							</span>
						</h4>
					</div>
					<div class="panel-body"  >
						<table class="table table-bordered" id="tabla_reporte">
							<thead> 
								<tr>
									<th>Clave</th>
									<th>Nombre</th>
									<th>Descripción</th>
									<th class="hidden-print">Eliminar</th>
								</tr>
							</thead>
							<tbody id="lista_unidades"> 
								
							</tbody>
						</table>
						
					</div>
				</div>
			</div>
		</div>
	</div>

	
		<?php  include('scripts.php'); ?>
		<script src="js/unidades.js"></script>
		
  </body>
</html>
