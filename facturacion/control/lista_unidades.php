<?php 
include("../conexi.php");
$link = Conectarse();
$id_emisores =$_GET["id_emisores"];

$query ="SELECT * FROM unidades 
	LEFT JOIN unidades_emisor USING(id_unidades) 
	WHERE id_emisores = '$id_emisores'";
	
	
$result =mysqli_query($link,$query) or die("Error en: $query  ".mysqli_error($link));

if(mysqli_num_rows($result) == 0){
	
	echo "<tr><td colspan='4'>
		<div class='alert alert-warning text-center'>No hay Unidades Asignadas</div>
		</td></tr>";
}
else{
	
	
	
	while($fila = mysqli_fetch_assoc($result)){
		extract($fila);
		
?>
	<tr>
		<td class="text-center"><?php echo $id_unidades; ?></td>
		<td class="text-center"><?php echo $nombre_unidades;?></td>
		<td class="text-center"><?php echo $descripcion_unidades;?></td>
		<td class="text-center hidden-print">
			<button class="btn btn-danger btn_eliminar" type="button" title="Eliminar" data-id_value="<?php echo $id_unidades_emisor; ?>"><i class="fa fa-trash" ></i>
			</button>
		
		</td>
	</tr>
	<?php 
			
	}
}
?>