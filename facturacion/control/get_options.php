<?php	
$formato = $_GET["formato"];	
$tabla = $_GET["tabla"];	
$id_col = $_GET["id_col"];
$nombre_col = $_GET["nombre_col"];

if(isset($_GET["etiqueta_vacia"])){
	$etiqueta_vacia = $_GET["etiqueta_vacia"];
}else{
	$etiqueta_vacia = "Elige...";
}

if(isset($_GET["filtros"])){
	
	
}	else{
	$filtro_campo = $_GET["filtro_campo"];		
	$filtro_valor = $_GET["filtro_valor"];	
	
}	
	
$campo_orden = $_GET["campo_orden"];
include("../conexi.php");	
$link=Conectarse();		
if($formato ==  "html"){
	
	$respuesta = "";		
	
		if(isset($filtro_campo)){
			$query = "SELECT *  FROM $tabla WHERE $filtro_campo = '$filtro_valor' ORDER BY $campo_orden ";		
			
		}else{
			$query = "SELECT * ";
			
			if(isset($_GET["etiqueta_campos"])){
				$etiqueta_campos = implode($_GET["etiqueta_campos"] ,", ");
				$query .= ", ".$etiqueta_campos;
			}	
			
			$query.= " FROM ".$tabla ;
			
			if(isset($_GET["joins"])){
				$joins = ""; 
				foreach($_GET["joins"] as $index => $join){
					$joins.= " LEFT JOIN "; 
					$joins.= $join["tabla"];
					
					$joins.= " USING(".$join["using"].") ";
				}
				$query .= $joins;
			}
			if(isset($_GET["filtros"])){
				$filtros = " WHERE "; 
				foreach($_GET["filtros"] as $index => $filtro){
					if( $filtro["operador"] == "="){
						$filtros.= $filtro["campo"] . $filtro["operador"]. "'".$filtro["valor"]."' AND " ;
					}
				}
				$filtros = substr($filtros, 0, -4);;
				
				$query .= $filtros;
			}
			
			$query .= " ORDER BY ".$campo_orden;
		}
	
	$result = mysqli_query( $link, $query )	;

	$respuesta="<option value=''>".$etiqueta_vacia."</option>";
	if($result ){
		while($row = mysqli_fetch_assoc($result)) {	
			$respuesta.= "<option value='".$row[$id_col]."' ";
			
			if(isset($_GET["data_campos"])){
				
				foreach($_GET["data_campos"] as $index => $data_campos){
					$respuesta.=" data-".$data_campos."='".$row[$data_campos]."' " ;
				}
				
			}
			
			$respuesta.=">";
			$respuesta.= $row[$nombre_col];
			
			if(isset($_GET["etiquetas"])){
				
				foreach($_GET["etiquetas"] as $index => $etiquetas){
					$respuesta.= "-".$row[$etiquetas] ;
				}
				
			}
			
			$respuesta.="</option>";
			
		}		
	
		echo $respuesta;
	}
	else{
		echo "Error en $query: " . mysqli_error($link);
	}
}
else{


}
// header('Content-Type: application/json');	

// $respuesta = array();		
		
// $query = "SELECT *  FROM $tabla ORDER BY $order";		
// $result = mysqli_query( $link, $query_complete )	;

// if($result ){
	
	
// }
// else{
	
	
// }

// while($row = mysql_fetch_assoc($result_complete)) {	
	// $response["data"][] = array($row["$id_col"], $row["$campo"]);	
// }		
	
// print(json_encode($response));
?>