<?php
	
	include("../login/login_success.php");
	include("../conexi.php");
	$link = Conectarse();
	$menu_activo = "catalogos";
	$consulta = "SELECT * FROM proveedores";
	$result = mysqli_query($link, $consulta);
	
	if($result){
		while($fila = mysqli_fetch_assoc($result)){
			$proveedores[] = $fila;
		}
	}
	else{ 
		die("Error en la consulta $consulta". mysqli_error($link));
	}
	
?>

<!DOCTYPE html>
<html lang="es">
	
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
			#btn_buscar {
			position: relative;
			top: 25px;
			}
		</style>
		<title>Calidades</title>
		
		<?php include("../styles_carpetas.php"); ?>
		
	</head>
	
	<body>
		<div class="container-fluid">
			<?php include("../menu_carpetas.php"); ?>
		</div>
		<section class="container">
			<strong>
				<h2>Calidades</h2>
			</strong>
			<hr>
			<div class="col-md-12 text-right">
				<button id="nuevo" type="button" class="btn btn-success" >
					<i class="fa fa-plus"></i> Nuevo
				</button>
			</div >
		</section>
		<br>
		
		<section class="container" id="lista_registros">
		
		</section>
		
		<?php include('../scripts_carpetas.php'); ?>
		<?php include('form_calidades.php'); ?>
		
		<script src="calidades.js"></script>
	</body>
</html>