<?php 
header("Content-Type: application/json");
include('../conexi.php');
$link = Conectarse();

$respuesta = array();


$id_facturas = $_GET['id_facturas'];

$consulta = "DELETE facturas, facturas_detalle FROM facturas LEFT JOIN facturas_detalle USING(id_facturas)
WHERE id_facturas = '$id_facturas'";

if(mysqli_query($link,$consulta)){
	$respuesta['estatus'] = 'success';
}else{
	$respuesta['estatus'] = 'error';
	$respuesta['mensaje'] = 'Error en DB'.mysqli_error($link);
}

echo json_encode($respuesta);
?>