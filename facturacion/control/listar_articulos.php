<?php
include("../conexi.php");
$link = Conectarse();

	$q_articulos = "SELECT * FROM articulos";
	$result_articulos = mysqli_query($link,$q_articulos) or die("Error en: $q_articulos ".mysqli_error($link));
	$count_rows = mysqli_num_rows($result_articulos);
	
	if($count_rows <= 0){
		?>
		
		<div class="alert alert-warning text-center">
	  <strong>No hay articulos en existencia</strong> 
	</div>
		<?php
	}else{
?>
<div class="row">
	<div class="col-md-12">
		<table class="table">
			<thead>
				<tr>
					<th class="text-center">
						Nombre
					</th>
					<th  class="text-center">
						Descripcion
					</th>
					<th  class="text-center">
						Costos
					</th>
				</tr>
			</thead>
				<tbody>
					<?php
					$q_articulos = "SELECT * FROM articulos";
					$result_articulos = mysqli_query($link,$q_articulos) or die("Error en: $q_articulos ".mysqli_error($link));
					while($row = mysqli_fetch_assoc($result_articulos))
					{
					$id_articulo = $row["id_articulo"];
					$nombre_articulo = $row['nombre_articulo'];
					$descripcion_articulo = $row["descripcion_articulo"];
					$costo_articulo = $row["costo_articulo"];
					?>
					<tr>
					<td class="text-center"><?php echo $nombre_articulo;?></td>
					<td class="text-center"><?php echo $descripcion_articulo;?></td>
					<td class="text-center"><?php echo $costo_articulo;?></td>
					<td class="text-center">
						
						
						<button class="btn btn-warning btn_editar" data-id_articulo="<?php echo $id_articulo;?>"><i class="fa fa-pencil" aria-hidden="true"></i>
						</button>
						<button class="btn btn-danger btn_eliminar" data-id_articulo="<?php echo $id_articulo?>"><i class="fa fa-trash" aria-hidden="true"></i>
						</button>
						
					</td>
					</tr>
					<?php }?>
				</tbody>
		</table>
	</div>
</div>
<?php
	}
	?>