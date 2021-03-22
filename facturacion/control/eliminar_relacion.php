<?php 
header("Content-Type: application/json");
include('../conexi.php');
$link = Conectarse();

$respuesta = array();


$id_campo = $_POST['id_campo'];

$consulta = "DELETE maestros, materias_maestros FROM maestros 
LEFT JOIN materias_maestros ON maestros.id_maestros = materias_maestros.id_maestros
WHERE maestros.id_maestros = '$id_campo'";

if(mysqli_query($link,$consulta)){
	$respuesta['estatus'] = 'success';
}else{
	$respuesta['estatus'] = 'error';
	$respuesta['error'] = 'Error en DB'.mysqli_error($link);
}

echo json_encode($respuesta);
?>