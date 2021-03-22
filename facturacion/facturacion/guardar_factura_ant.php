<?php
header("Content-Type: application/json");
session_start();
// Se desactivan los mensajes de debug
error_reporting(~(E_WARNING|E_NOTICE));
date_default_timezone_set('America/Mexico_City');

//error_reporting(E_ALL);

include_once("../conexi.php");
$link = Conectarse();

$respuesta = array();
$datos = array();
$conceptos = array();



$id_emisores = $_SESSION["id_usuarios"];
$rfc = $_SESSION["rfc_emisores"];
$rfc_emisores = $_SESSION["rfc_emisores"];
$razon_social_emisores=  $_SESSION["razon_social_emisores"];

$regimen_emisores= $_POST["regimen_emisores"];
$id_clientes = $_POST["id_clientes"];
$rfc_clientes =  $_POST["rfc_clientes"];
$razon_social_clientes =  $_POST["razon_social_clientes"];


$serie = $_POST["serie"];
$folio = $_POST["folio"];
$folio_facturas = $serie.$folio ;

if($folio_facturas == ''){
	
	$folio_facturas = date("dmY_Hi");
}
$respuesta["folio_anterior"] = $folio_facturas;
	




$lugar_expedicion = $_POST["lugar_expedicion"];
$metodo_pago = $_POST["metodo_pago"];
$forma_pago = $_POST["forma_pago"];
$uso_cfdi= $_POST["uso_cfdi"];
$tipo_comprobante =$_POST["tipocomprobante"];

$subtotal =  $_POST["subtotal"];
$descuento_total =  $_POST["descuento_total"];
$iva_total = $_POST["iva_total"];
$total = $_POST["total_pagos"];

$observaciones = $_POST["observaciones"];





// Se especifica la version de CFDi 3.3
$datos['version_cfdi'] = '3.3';

// Ruta del XML Timbrado
$datos['cfdi']='timbrados/'.$rfc."_".$folio_facturas.'.xml';

// Ruta del XML de Debug
$datos['xml_debug']='timbrados/sin_timbrar'.$rfc."_".$folio_facturas.'.xml';


$produccion = isset($_POST["modo_pruebas"])? "NO" : "SI";
$timbrado = isset($_POST["modo_pruebas"])? 0 : 1;

// Credenciales de Timbrado
// IF($rfc == "GUAF880601NA6"){
	
	// $datos['PAC']['usuario'] = "fernandoguzman";
// }
// else{
	// $datos['PAC']['usuario'] = $rfc;
	
// }
$datos['PAC']['usuario'] = $rfc;
$datos['PAC']['pass'] = $pass_timbrado;
$datos['PAC']['produccion'] = $produccion;
 
// Rutas y clave de los CSD
$datos['conf']['cer'] = "certificados/$rfc.cer.pem";
$datos['conf']['key'] = "certificados/$rfc.key.pem";
$datos['conf']['pass'] = $pass_timbrado;


// Datos del Emisor
$datos['emisor']['rfc'] = $rfc_emisores; 
$datos['emisor']['nombre'] = $razon_social_emisores;  
$datos['factura']['RegimenFiscal'] = $regimen_emisores;

// Datos del Receptor
$datos['receptor']['rfc'] = $rfc_clientes;
$datos['receptor']['nombre'] = $razon_social_clientes;
$datos['receptor']['UsoCFDI'] = $uso_cfdi; 

// Datos de la Factura
$datos['factura']['fecha_expedicion'] = date('Y-m-d\TH:i:s', time() - 120);
$datos['factura']['serie'] = $serie;
$datos['factura']['folio'] = $folio;
 
$datos['factura']['forma_pago'] = $forma_pago;
$datos['factura']['LugarExpedicion'] = $lugar_expedicion; 
$datos['factura']['tipocomprobante'] = $tipo_comprobante;
$datos['factura']['metodo_pago'] = $metodo_pago;
$datos['factura']['moneda'] = 'MXN';


//Conceptos
if(isset($_POST["descripcion"])){
	$conceptos = array();
	foreach($_POST["descripcion"] as $indice => $descripcion){
						
			$datos['conceptos'][$indice]['cantidad'] = $_POST["cantidad"][$indice];
			$datos['conceptos'][$indice]['ClaveUnidad'] = $_POST["clave_unidad"][$indice];
			$datos['conceptos'][$indice]['unidad'] = $_POST["nombre_unidades"][$indice]; // mandar por post
			$datos['conceptos'][$indice]['ClaveProdServ'] = $_POST["clave_producto"][$indice];
			$datos['conceptos'][$indice]['descripcion'] = $_POST["descripcion"][$indice];
			$datos['conceptos'][$indice]['valorunitario'] = $_POST["precio_unitario"][$indice];
			$datos['conceptos'][$indice]['importe'] = $_POST["importe"][$indice];
			$datos['conceptos'][$indice]['Descuento'] = $_POST["descuento"][$indice];
			
			$datos['conceptos'][$indice]['Impuestos']['Traslados'][0]['Base'] = $_POST["importe"][$indice];
			$datos['conceptos'][$indice]['Impuestos']['Traslados'][0]['Impuesto'] = '002';
			$datos['conceptos'][$indice]['Impuestos']['Traslados'][0]['TipoFactor'] = 'Tasa';
			$datos['conceptos'][$indice]['Impuestos']['Traslados'][0]['TasaOCuota'] = '0.160000';
			$datos['conceptos'][$indice]['Impuestos']['Traslados'][0]['Importe'] = $_POST["iva"][$indice];


	}
	
	
	$respuesta["conceptos"] = $datos['conceptos'];
}
else{
	
	$respuesta["estatus"] = "Error";
	$respuesta["mensaje"] = "No hay Conceptos";

}

//Impuestos

$datos['impuestos']['translados'][0]['impuesto'] = '002';
$datos['impuestos']['translados'][0]['tasa'] = '0.160000';
$datos['impuestos']['translados'][0]['importe'] = $_POST["iva_total"];
$datos['impuestos']['translados'][0]['TipoFactor'] = 'Tasa';
$datos['impuestos']['TotalImpuestosTrasladados'] = $_POST["iva_total"];

//Totales
$datos['factura']['subtotal'] = $subtotal;
$datos['factura']['descuento'] = $descuento_total; 
$datos['factura']['total'] = $total;


//Insert Factura
$insert_facturas =" INSERT INTO facturas SET ";
$insert_facturas.=" folio_facturas = '". $folio_facturas . "',";
$insert_facturas.=" id_emisores = '". $id_emisores . "',";
$insert_facturas.=" fecha_facturas = CURDATE(),";
$insert_facturas.=" id_clientes = '". $id_clientes . "',";
$insert_facturas.=" metodo_pago = '". $metodo_pago . "',";
$insert_facturas.=" forma_pago = '". $forma_pago . "',";
$insert_facturas.=" lugar_expedicion = '". $lugar_expedicion . "',";
$insert_facturas.=" subtotal = '". $subtotal . "',";
$insert_facturas.=" iva_total = '". $iva_total . "',";
$insert_facturas.=" descuento = '". $descuento_total . "',";
$insert_facturas.=" total = '". $total . "',";
$insert_facturas.=" tipo_comprobante = '". $tipo_comprobante . "',";
$insert_facturas.=" uso_cfdi = '". $uso_cfdi . "',";
$insert_facturas.=" url_pdf = '". 'timbrados/'.$rfc."_".$folio_facturas.'.pdf' . "',";
$insert_facturas.=" timbrado = 0, ";
$insert_facturas.=" observaciones = '$observaciones'";

$result = mysqli_query($link, $insert_facturas);

if($result){
	$respuesta["insert_facturas_estatus"]  = "success";
	$respuesta["insert_facturas_mensaje"]  = "Factura Guardada Correctamente";
	$id_facturas =  mysqli_insert_id($link);
	$respuesta["id_facturas"]  = $id_facturas;
	foreach($datos["conceptos"] as $index=>$concepto){
		
		$clave_productos= $concepto['ClaveProdServ'];
		$clave_unidad = $concepto['ClaveUnidad'];
		$cantidad = $concepto['cantidad'];
		$unidad = $concepto['unidad'];
		$descripcion	 = $concepto['descripcion'];
		$precio	 = $concepto['valorunitario'];
		$importe	 = $concepto['importe'];
		
		$insert_detalle	= "INSERT INTO facturas_detalle SET 
			id_facturas = '$id_facturas', 
			clave_productos = '$clave_productos', 
			clave_unidad = '$clave_unidad', 
			cantidad = '$cantidad', 
			unidad = '$unidad', 
			descripcion = '$descripcion', 
			precio = '$precio', 
			importe = '$importe'";

		if(mysqli_query($link, $insert_detalle)){
			$respuesta["insert_detalle"][$index]["estatus"]  = "success";
			$respuesta["insert_detalle"][$index]["query"]  = $insert_detalle;
		}
		else{
			$respuesta["insert_detalle"][$index]["estatus"]  = "error";
			$respuesta["insert_detalle"][$index]["mensaje"]  = mysqli_error($link);
			$respuesta["insert_detalle"][$index]["query"]  = $insert_detalle;
		
		}
	}
}
else{
	$respuesta["insert_facturas_estatus"]  = "error";
	$respuesta["insert_facturas_mensaje"]  = mysqli_error($link);

}

$respuesta["insert_facturas"]  = $insert_facturas;





echo json_encode($respuesta);



