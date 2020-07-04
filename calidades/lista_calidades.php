<?php
	include('../conexi.php');
	$link = Conectarse();
	
	$consulta = "SELECT * FROM calidades ORDER BY calidad";
	
	
	$result = mysqli_query($link,$consulta);
	
	if(!$result){
		die("Error en $consulta" . mysqli_error($link) );
	}
	else{
		$num_rows = mysqli_num_rows($result);
		if($num_rows != 0){
			while($row = mysqli_fetch_assoc($result)){
				$filas[] = $row;
			}	
			
		?>
		
		
		
		<table class="table table-striped">
			<tr class="success">
				<td><strong>Calidad</strong></td>
				<td><strong>Acciones</strong></td>
			</tr>
			<?php foreach($filas AS $i=>$fila){	?>
				<tr class="">
				
					<td><?php echo $fila["calidad"] ?></td> 
					<td>
						<button class="btn btn-warning btn_editar" type="button" data-id_registro="<?php echo $fila["id_calidades"]?>" >
							<i class="fas fa-edit" ></i> Editar
						</button>
						<button class="btn btn-danger btn_borrar" type="button" data-id_registro="<?php echo $fila["id_calidades"]?>" >
							<i class="fas fa-trash" ></i> Borrar
						</button>
					</td> 
				</tr>
				<?php
				}
			}
			else{ ?>
			<tr>
				<td class="text-center" colspan="7">
					<h2 class="text-center">No hay registros</h2>
				</td>
			</tr>
		</table>
		
		<?php
		}
	}
?>



