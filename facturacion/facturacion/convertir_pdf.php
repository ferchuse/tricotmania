<?php
header("Content-Type: application/json");

$respuesta = Array();

include('../../mpdf57/mpdf.php');
$mpdf = new mPDF();

$mpdf->debug = true;

$mpdf->WriteHTML("Hallo World");

$mpdf->Output();

//$mpdf->WriteHTML($html);
// try{
	// $mpdf->Output($url_pdf);  

// }
// catch(Exception $e){
	// $respuesta["estatus_pdf"] = "Error";
	// $respuesta["mensaje_pdf"] = $e->getMessage();;
	
// }

echo json_encode($respuesta);
?>