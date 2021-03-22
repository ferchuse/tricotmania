<?php
header("Content-Type: application/json");
require_once("../lib_php/conekta_2_0/Conekta.php");
\Conekta\Conekta::setApiKey("key_7DiRiq2ymMRqrN2YGozk7g");
\Conekta\Conekta::setApiVersion("2.0.0");
$respuesta = array();

try{
  $order = \Conekta\Order::create(
    array(
      "line_items" => array(
        array(
          "name" => "Tacos",
          "unit_price" => 1000,
          "quantity" => 12
        )//first line_item
      ), //line_items
      "currency" => "MXN",
      "customer_info" => array(
        "name" => "Fulanito Pérez",
        "email" => "fulanito@conekta.com",
        "phone" => "+5218181818181"
      ), //customer_info
     
      "charges" => array(
          array(
              "payment_method" => array(
                "type" => "oxxo_cash",
								 'expires_at' => strtotime(date("Y-m-d H:i:s")) + "36000"
              )//payment_method
          ) //first charge
      ) //charges
    )//order
  );
	
	 $respuesta["status"] = "success";
	 $respuesta["order"] = $order;
} 
catch (\Conekta\ParameterValidationError $error){
  $respuesta["status"] = "error";
	$respuesta["mensaje"] = $error->getMessage();
} 
catch (\Conekta\Handler $error){
 $respuesta["status"] = "error";
 $respuesta["mensaje"] = $error->getMessage();
}


echo json_encode($respuesta);
?>