<?php
	include("../login/login_success.php");
	include("../funciones/generar_select.php");
	include("../conexi.php");
	$link = Conectarse();
	
	$menu_activo = "clientes";
	
?>
<!DOCTYPE html>
<html lang="es">
	
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>Clientes</title>
		
		<?php include("../styles_carpetas.php"); ?>
		<style>
			.asc::after {
			content: "<i class='fas fa-arrow-down'></i>";
				
				}
			</style>
		</head>
		
		<body>
			<?php include("../menu_carpetas.php"); ?>
			
			<div class="container-fluid d-print-none">
				<div class="row">
					<div class="col-12 border-bottom mb-3">
						<h3 class="text-center">Proveedores <span class="badge 
badge-success" id="contar_registros">0</span></h3>
					</div>
				
				<div class="row col-12 mb-4">
				<div class="col-12 col-sm-3" >
					<input class="buscar  form-control float-left" type="search" placeholder="Buscar">
					
				</div>
				<div class="col-sm-7">
					<form class="form-inline" id="form_filtros">
						<input type="hidden" id="sort" name="sort" value="nombre_proveedores">
						<input type="hidden" id="order" name="order" value="ASC">
						
					
						
						<button type="submit" class="btn btn-primary" >
							<i class="fa fa-search"></i>
						</button>
						
					</form>
				</div>
				<div class="ml-auto">
					<button type="button" class="btn btn-success float-right" id="btn_nuevo">
						<i class="fa fa-plus"></i> Nuevo
					</button>
				</div>
				</div>
				
				
				
				<div class="text-center table-responsive" id="lista_registros" >
					
				</div>
				
				</div>
		</div>
		
		<div id="historial">
		</div>
		
		<?php include('../scripts_carpetas.php'); ?>
		<?php include('form_cargos.php'); ?>
		<?php include('form_clientes.php'); ?>
		<script src="clientes.js?v=<?= date("YmdHis")?>"></script>
		<script src="cargos.js?v=<?= date("YmdHis")?>"></script>
		
	</body>
	
</html>										