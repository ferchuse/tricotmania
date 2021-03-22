<?php
	
	
	function timbres_restantes($link, $id_emisores){
		$repuesta = array();
		
		
		$respuesta["consulta"] = "SELECT folios_restantes_emisores FROM emisores WHERE id_emisores = '$id_emisores'";
		
		$respuesta["result"] = mysqli_query($link, $respuesta["consulta"]);
		
	
		$respuesta["fila"] = mysqli_fetch_assoc($respuesta["result"]);
		
		
		return $respuesta;
		
	}
	
	
?>