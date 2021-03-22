<?php 
	
	include("conexi.php");
	include("control/is_selected.php");
	$link= Conectarse();
	$menu_activo = "usuario";
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Usuarios</title>

	<?php include("styles.php");?>
	
  </head>
  <body>

    <div class="container-fluid">
		<?php include("menu.php");?>
		  
		<div class="row content">
			<div id="buscar_reporte" class="central col-sm-12 text-left"> 	
				
				<h4>
					<div class="row">
						<div class="col-sm-2 ">
							<button  id="btn_agregar" type="button"  class="btn btn-success ">
								<i class="fa fa-plus"></i> Agregar <i id="loading" class="fa fa-spinner fa-spin hide"></i>
							</button>
						</div>
						<div class="col-sm-2 ">
							<label >
								Folios
							</label>
						</div>
					</div>
				</h4>
				<hr>
				
				<div class="row " >
					<div class="table-responsive" id="div_tabla">
						<table class="table">
							<th class="text-center">Nombre	</th>
							<th class="text-center">Usuario</th>
							<th class="text-center">Contraseña</th>
							<th class="text-center">Permisos</th>
							
							<th class="text-center"></th>
						<?php
							$q_usuarios ="SELECT * FROM pedidos_folios  ";
							$result_usuarios=mysqli_query($link,$q_usuarios) or die("Error en: $q_usuarios  ".mysqli_error($link));
								
							while($row = mysqli_fetch_assoc($result_usuarios)){
								$id_usuario = $row["id_usuario"];
								$nombre_usuario = $row["nombre_completo"];
								$usuario = $row["usuario"];
								$password = $row["pass"];
								$permisos = $row["permisos"];

							?>
							<tr>
								<td>
								
								</td>
							
							</tr>
							<?php
							}
							?>
						</table>
					</div> 
				</div>
			</div>
		</div>
	
	<form id="form_nuevo_usuario" class="form" role="form">
		<div id="modal_nuevo_usuario" class="modal fade" role="dialog">
		  <div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Nuevo Usuario</h4>
			  </div>
			 
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label for="color_ticket">
									Nombre :
								</label>
								<input required type="text" class="form-control" name="nombre_completo" id="nombre_completo"  >
							</div>
							<div class="form-group">
								<label for="usuario">
									Usuario :
								</label>
								<input required type="text" class="form-control" name="usuario" id="usuario"  >
							</div>
							<div class="form-group">
								<label for="pass">
									Contraseña :
								</label>
								<input required type="password" class="form-control" name="pass"  id="pass" >
							</div>
							<div class="form-group">
								<label for="permisos">
									Permisos:
								</label>
								<select required id="permisos" class="form-control" name="permisos">
									<option  value="">Elige...</option>
									<option  value="Administrador">Administrador</option>
									<option value="Usuario">Usuario</option>
								</select>	
							</div>
						</div>
					</div>
				</div>
			  
			  <div class="modal-footer">
				
				<button type="button" class="btn btn-danger" data-dismiss="modal">
					<i class="fa fa-times"></i> Cerrar
				</button>
				<button type="submit" id="btn_insert" class="btn btn-success" >
					<i class="fa fa-save"></i> Guardar <i class="fa fa-spinner fa-spin hide"></i> 
				</button>
			  </div>
			</div>
		  </div>
		</div>
		</form>
	</div>																
	
	<?php include("scripts.php");?>
	<script src="js/folios.js"></script>
	
  </body>
</html>