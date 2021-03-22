<?php
	include("login/login_success.php");
	include_once("conexi.php");
	$link = Conectarse();
	$menu_activo = "facturas";
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Clientes</title>

<?php include("styles.php");?>

</head>
<body>

<div class="container-fluid">
	<?php include("menu.php");?>
</div>

<div class="container">
	<h2 class="text-center">Clientes <span id="total_clientes" class="badge badge-success"></span>
		
	<button type="button" class="btn btn-success pull-right" id="btn_insert">
			<i class="fa fa-plus" ></i> Agregar
	</button>	
	</h2>
	<hr>

		<div class="row">
			<div class="col-md-12">
				<input class="form-control"  id="buscar_cliente" placeholder="Escribe para Buscar">	
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<table class="table">
					<thead>
						<tr>
							<th  class="text-center">Nombre</th>
							<th  class="text-center">RFC</th>
							<th  class="text-center">Acciones</th>
						</tr>
					</thead>
					<tbody id="cuerpo">
						<tr><td class='text-center' colspan='5'><i class='fa fa-spinner fa-spin fa-3x'></i></td></tr>
					</tbody>
				</table>
			</div>
		</div>
</div>
		
	
	<?php include('forms/clientes.php'); ?>
	
	<?php  include('scripts.php'); ?>
	<script src="js/clientes.js"></script>
	
  </body>
</html>
