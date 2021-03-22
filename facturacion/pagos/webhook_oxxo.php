<?php
http_response_code(200); // Return 200 OK
// header("Content-Type: application/json");
include("../conexi.php");
$link = Conectarse();
$respuesta = array();

$body = file_get_contents('php://input');
$data = json_decode($body);


echo "test";
// if ($data->type == 'charge.paid'){
  // $msg = "Tu pago ha sido comprobado.";
  // mail("<a href='mailto:sistemas@glifo.mx'>sistemas@glifo.mx</a>","Pago confirmado",$msg);
	
	// $insert = "INSERT INTO conekta_events SET mensaje = '$body' , type = '".$data->type."'";
	
	// $result = mysqli_query($link ,  $insert);
	
	
// }







?>