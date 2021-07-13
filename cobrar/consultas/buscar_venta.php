<?php 
	header("Content-Type: application/json");
	include('../../conexi.php');
	$link = Conectarse();
	$respuesta = array();
	$consulta = "SELECT estatus_ventas, SUM(total_ventas) as total_ventas FROM ventas 
	WHERE id_ventas IN ({$_GET["id_ventas"]})
	
	";    
	
	$respuesta["consulta"] = $consulta;
	$result = mysqli_query($link,$consulta);
	
	
	
	if(!$result){
        die("Error en $consulta" . mysqli_error($link) );
	}
	else{
		$num_rows = mysqli_num_rows($result);
		$respuesta["num_rows"] = $num_rows;
		
		if($num_rows != 0){
			while($row = mysqli_fetch_assoc($result)){
				$respuesta["venta"] = $row;        
			}
		}
		else{
			
		}
	}
	
	
    echo json_encode($respuesta);
?>
