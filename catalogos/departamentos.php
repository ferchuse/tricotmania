<?php
	include("../funciones/generar_select.php");
	include("../login/login_success.php");
	include("../conexi.php");
	$link = Conectarse();
	$menu_activo = "catalogos";
	$consulta = "SELECT * FROM departamentos ORDER BY nombre_departamentos";
	$result = mysqli_query($link, $consulta);
	
	if($result){
		while($fila = mysqli_fetch_assoc($result)){
			$departamentos[] = $fila;
		}
	}
	else{ 
		die("Error en la consulta $consulta". mysqli_error($link));
	}
	// echo "<script> console.log()"
	
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
		<title>Departamentos</title>
		
		<?php include("../styles_carpetas.php"); ?>
		
	</head>
	
	<body>
		<div class="container-fluid">
			<?php include("../menu_carpetas.php"); ?>
		</div>
		<section class="container">
			<strong>
				<h2>Departamentos</h2>
			</strong>
			<hr>
			<!-- Button Modal Proveedores -->
			<div class="col-md-12 text-right">
				<button id="nuevo" type="button" class="btn btn-success" >
					<i class="fa fa-plus"></i> Nuevo
				</button>
			</div >
		</section>
		<br>
		
		<section class="container">
			<table class="table table-striped">
				<tr class="success">
					<td><strong>Departamento</strong></td>
					<td><strong>Piezas Descuento</strong></td>
					<td><strong>% Descuento</strong></td>
					<td><strong>Acciones</strong></td>
				</tr>
				<?php foreach($departamentos AS $i=>$fila){	?>
					<tr class="">
						
						<td><?php echo $fila["nombre_departamentos"] ?></td> 
						<td><?php echo $fila["piezas_descuento"] ?></td> 
						<td><?php echo $fila["porc_descuento"] ?></td> 
						<td>
							<button class="btn btn-warning btn_editar" type="button" 
							data-id_registro="<?php echo $fila["id_departamentos"]?>"
							>
								<i class="fas fa-edit" ></i> Editar
							</button>
							<button class="btn btn-danger btn_borrar" 
							data-id_registro="<?php echo $fila["id_departamentos"]?>">
								<i class="fa fa-trash"></i>
							</button>
							
						</td> 
					</tr>
					<?php
					}
				?>
			</table>
			</section>
		
		
		
		
		
		<?php include('../scripts_carpetas.php'); ?>
		<?php include('form_departamentos.php'); ?>
		
		
	</body>
	<script>
		$("#nuevo").click(function(){
			$("#modal_edicion").modal("show")
			
		});
		
		$("#form_edicion").submit(guardarRegistro);
		$(".btn_editar").click(cargarDatos);
		$(".btn_borrar").click(borrarRegistro);
		
		function cargarDatos(event){
			console.log("event", event);
			let $boton = $(this);
			let $icono = $(this).find(".fas");
			let $id_registro = $(this).data("id_registro");				
			$boton.prop("disabled", true);
			$icono.toggleClass("fa-edit fa-spinner fa-spin");				
			$.ajax({ 
				"url": "../funciones/fila_select.php",
				"dataType": "JSON",
				"data": {
					"tabla": "departamentos",
					"id_campo": "id_departamentos",
					"id_valor": $id_registro						
				}
				}).done( function alTerminar (respuesta){					
				console.log("respuesta", respuesta);
				$boton.prop("disabled", false);
				$icono.toggleClass("fa-edit fa-spinner fa-spin"); 
				$("#modal_edicion").modal("show")
				$("#id_departamentos").val(respuesta.data.id_departamentos);                        
				$("#nombre_departamentos").val(respuesta.data.nombre_departamentos);                        
				$("#piezas_descuento").val(respuesta.data.piezas_descuento);                        
				$("#porc_descuento").val(respuesta.data.porc_descuento);                        
				
			})
		}
		
		function guardarRegistro(event){
			event.preventDefault()
			let $boton = $(this).find(':submit');
			let $icono = $(this).find(".fas");
			$boton.prop("disabled", true);
			$icono.toggleClass("fa-save fa-spinner fa-spin");
			console.log("guardarRegistro")
			$.ajax({ 
				"url": "guardar_catalogo.php",
				"dataType": "JSON",
				"method": "POST",
				"data": {
					"tabla": "departamentos",
					"id_campo": $("#id_departamentos").val(),
					"name": $("#nombre_departamentos").val(),
					"piezas_descuento": $("#piezas_descuento").val(),
					"porc_descuento": $("#porc_descuento").val()
					
				}
				}).done( function alTerminar (respuesta){
				console.log("respuesta", respuesta);
				$boton.prop("disabled", false);
				$icono.toggleClass("fa-save fa-spinner fa-spin"); 
				$("#modal_edicion").modal("hide");
				window.location.reload(true);
			});
			// return false;
		}		
		
		function borrarRegistro() {
			console.log("borrarRegistro()");
			let boton = $(this);
			let icono = boton.find(".fas");
			let id_registro = boton.data("id_registro");
			
			if(confirm("Esta Seguro?")){
				
				boton.prop("disabled", true);
				icono.toggleClass("fa-trash fa-spinner fa-spin");
				
				$.ajax({
					url: "../funciones/fila_delete.php",
					method: "POST",
					dataType: "JSON",
					data: {
						"tabla": "departamentos",
						"id_campo": "id_departamentos",
						id_valor: id_registro
						
					}
					
					}).done(function (respuesta) {
					console.log("respuesta", respuesta);
					
					boton.closest("tr").remove();
					
					}).fail(function (xht, error, errnum) {
					
					alertify.error("Error", errnum);
					}).always(function () {
					boton.prop("disabled", false);
					icono.toggleClass("fa-trash fa-spinner fa-spin");
					
				});
			}
			
		}
	</script>
</html>