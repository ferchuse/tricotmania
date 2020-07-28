<?php
	include("../login/login_success.php");
	include("../conexi.php");
	include("../funciones/generar_select.php");
	$link = Conectarse();
	$menu_activo = "producto";
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
			#respuesta_rep{
			color: red;
			}
		</style>
    <title>Productos</title>
		
		<?php include("../styles_carpetas.php");?>
		
	</head>
  <body>
		
		<?php include("../menu_carpetas.php");?>
		
		
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<h2 class="text-center">Productos <span class="badge badge-success" id="count_rows"></span></h2>
					<hr>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<form id="form_filtros" class="form-inline">
						<div class="form-group">
							<label for="fecha_inicio">Departamento:</label>
							<?php echo generar_select($link, "departamentos", "id_departamentos", "nombre_departamentos", true)?>
						</div>
						<div class="form-group">
							<label for="fecha_fin">Existencias:</label>
							<select  class="form-control"  name="existencia">
								<option value="">TODAS</option>
								<option value="minimo">DEBAJO DEL MINIMO</option>
							</select>
						</div>
						
						<button type="submit" class="btn btn-primary" id="btn_buscar">
							<i class="fa fa-search"></i> Buscar
						</button>
					</form>
					<button type="button" class="btn btn-success pull-right" id="btn_alta">
						<i class="fa fa-plus"></i> Nuevo
					</button>
					<button hidden type="submit" form="form_imprimir_precios" class="btn btn-info pull-right hidden" form="btn_imprimir_precios">
						<i class="fa fa-print"></i> Imprimir Precios  
						(<span id="cant_seleccionados">0</span>)
					</button>
					<a class="btn btn-info pull-right" href="export_excel.php" target="">
						<i class="fa fa-file-excel"></i> Exportar  
					</a>
					<input type="hidden" id="productos_seleccionados">
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-md-12 text-center table-responsive" id="lista_productos">
					
					<table class="table table-bordered" id="tabla_productos">
						<thead class="bg-primary">
							<tr>
								<th class="text-center">Descripción</th>
								<th class="text-center">Departamento</th>
								<th class="text-center">Calidad</th>
								<th class="text-center">Precio Público</th>
								<th class="text-center">Precio Mayoreo</th>
								<th class="text-center">Precio Distribuidor</th>
								<th class="text-center">Precio Fábrica</th>
								<th class="text-center">Mínimo</th>
								<th class="text-center">Existencia</th>
								<th class="text-center">Acciones</th>	
							</tr>
							<tr>
								<th class="text-center">
									<input type="text" autocomplete="off" name="descripcion_productos" form="form_filtros" class="form-control buscar_descripcion" data-indice="0" placeholder="Buscar descripcion">
								</th>
								<th colspan="8">
								</th>
								<th >
									<input type="checkbox" id="check_all">
								</th>
							</tr>
						</thead>
						
						<tbody id="bodyProductos">                    
							<tr>
								<th class="text-center" colspan="9">
									<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
								</th>
							</tr>                   
							
						</tbody>
						
					</table>
					
				</div>
			</div>
		</div>
		<form id="form_imprimir_precios" action="../impresion/imprimir_precios.php">
		</form>
		<div id="historial">
		</div>
		<?php include('form_productos.php'); ?>
		<?php include('existencias.php'); ?>

		<?php  include('../scripts_carpetas.php'); ?>
		<script src="productos.js?v=<?= date("d-m-Y-H-i-s")?>"></script>
		<script src="carrito.js"></script>
		<script src="https://unpkg.com/sticky-table-headers"></script>
	</body>
</html>