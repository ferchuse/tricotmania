<?php 
include("../conexi.php");
$link = Conectarse();
$id_emisores =$_GET["id_emisores"];

$query ="SELECT * FROM productos 
	LEFT JOIN productos_emisor USING(id_productos) 
	WHERE id_emisores = '$id_emisores' 
	ORDER BY descripcion_productos";
	
	
$result =mysqli_query($link,$query) or die("Error en: $query  ".mysqli_error($link));

if(mysqli_num_rows($result) == 0){
	
	echo "<tr><td colspan='4'>
		<div class='alert alert-warning text-center'>No hay Productos Asignados</div>
		</td></tr>";
}
else{
	
	
	
	while($fila = mysqli_fetch_assoc($result)){
		extract($fila);
		
?>
	<tr>
		<td class="text-center"><?php echo $id_productos; ?></td>
		<td class="text-center"><?php echo $descripcion_productos;?></td>
		<td class="text-center hidden-print">
			<button class="btn btn-danger btn_eliminar" type="button" title="Eliminar" data-id_value="<?php echo $id_productos_emisor; ?>"><i class="fa fa-trash" ></i>
			</button>
		
		</td>
	</tr>
	<?php 
			
	}
}
?>