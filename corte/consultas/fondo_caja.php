<?php 
include ('../../conexi.php');
$link = Conectarse();

$respuesta = array();

setcookie("efectivo_inicial", $_POST["efectivo_inicial"],  0, "/");

$consulta = "UPDATE turnos SET 
efectivo_inicial = '{$_POST["efectivo_inicial"]}'
WHERE id_turnos = '{$_POST["id_turnos"]}'
";

if(mysqli_query($link,$consulta)){
	$respuesta['estatus'] = "success";
}else{
	$respuesta['estatus'] = "error";
	$respuesta['mensaje'] = "Error en ".mysqli_error($link);
}

echo json_encode($respuesta);

?>