<?php
header("Content-Type: application/json");
session_start();
error_reporting(~(E_WARNING|E_NOTICE));
//error_reporting(E_ALL);

include_once("../conexi.php");
require_once 'sdk2.php';


$link = Conectarse();

$respuesta = array();
$datos = array();

//datos del Emisor
$rfc = $_SESSION["rfc_emisores"];
$pass_timbrado = $_SESSION["password"];
$clave_privada = $_SESSION["password"];


//datos de la factura
$id_facturas = $_GET["id_facturas"];


$q_factura = "SELECT * FROM facturas
LEFT JOIN clientes
USING(id_clientes)
WHERE id_facturas = '$id_facturas'";

$result_factura =  mysqli_query($link, $q_factura);

if($result_factura){
	
	
	while($fila_factura = mysqli_fetch_assoc($result_factura)){
	
		// Datos del Receptor
		$datos['receptor']['rfc'] = $fila_factura["rfc_clientes"];
		$datos['receptor']['nombre'] = $fila_factura["razon_social_clientes"];
		$datos['receptor']['UsoCFDI'] = $fila_factura["uso_cfdi"]; 

		// Datos de la Factura
		$datos['factura']['fecha_expedicion'] = date('Y-m-d\TH:i:s', time() - 120);
		$datos['factura']['serie'] =  $fila_factura["serie"];
		$datos['factura']['folio'] =  $fila_factura["folio"];
		 
		$datos['factura']['forma_pago'] = $fila_factura["forma_pago"];
		$datos['factura']['LugarExpedicion'] =  $fila_factura["lugar_expedicion"]; 
		$datos['factura']['tipocomprobante'] = $fila_factura["tipo_comprobante"]; 
		$datos['factura']['metodo_pago'] = $fila_factura["metodo_pago"]; 
		$datos['factura']['moneda'] = 'MXN';

	}
}
if($folio_facturas == ''){
	
	$folio_facturas = date("dmY_Hi");
}

$id_emisores = $_SESSION["id_usuarios"];
$rfc_emisores = $_SESSION["rfc_emisores"];
$razon_social_emisores=  $_SESSION["razon_social_emisores"];
$regimen_emisores= $_POST["regimen_emisores"];

$id_clientes = $_POST["id_clientes"];
$rfc_clientes =  $_POST["rfc_clientes"];
$razon_social_clientes =  $_POST["razon_social_clientes"];




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
$conceptos = array();



date_default_timezone_set('America/Mexico_City');


// Se especifica la version de CFDi 3.3
$datos['version_cfdi'] = '3.3';

// Ruta del XML Timbrado
$datos['cfdi']='timbrados/'.$rfc."_".$folio_facturas.'.xml';

// Ruta del XML de Debug
$datos['xml_debug']='timbrados/sin_timbrar'.$rfc."_".$folio_facturas.'.xml';


$produccion = isset($_POST["modo_pruebas"])? "NO" : "SI";
$timbrado = isset($_POST["modo_pruebas"])? 0 : 1;


$datos['PAC']['usuario'] = $_SESSION["rfc_emisores"];
$datos['PAC']['pass'] = $pass_timbrado;
$datos['PAC']['produccion'] = $produccion;
 
// Rutas y clave de los CSD
$datos['conf']['cer'] = "certificados/$rfc.cer.pem";
$datos['conf']['key'] = "certificados/$rfc.key.pem";
$datos['conf']['pass'] = $pass_timbrado;


// Datos del Emisor
$datos['emisor']['rfc'] = $_SESSION["rfc_emisores"];
$datos['emisor']['nombre'] = $_SESSION["razon_social_emisores"]; 
$datos['factura']['RegimenFiscal'] = $_SESSION["regimen_emisores"];

$q_detalle = "SELECT * FROM facturas_detalle WHERE "

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


$respuesta["datos_enviados"]  = $datos;

// Se ejecuta el SDK
$respuesta["timbrado"] = mf_genera_cfdi($datos);



if($respuesta["timbrado"]["codigo_mf_numero"] == 0){
	
	// TODO guardar en BD
	
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
	$insert_facturas.=" archivo_xml = '". $respuesta["timbrado"]["archivo_xml"] . "',";
	$insert_facturas.=" archivo_png = '". $respuesta["timbrado"]["archivo_png"] . "',";
	$insert_facturas.=" uuid = '". $respuesta["timbrado"]["uuid"] . "',";
	$insert_facturas.=" representacion_impresa_cadena = '". $respuesta["timbrado"]["representacion_impresa_cadena"] . "',";
	$insert_facturas.=" representacion_impresa_certificado_no = '". $respuesta["timbrado"]["representacion_impresa_certificado_no"] . "',";	
	$insert_facturas.=" representacion_impresa_fecha_timbrado = '". $respuesta["timbrado"]["representacion_impresa_fecha_timbrado"] . "',";$insert_facturas.=" representacion_impresa_sello = '". $respuesta["timbrado"]["representacion_impresa_sello"] . "',";
	$insert_facturas.=" representacion_impresa_selloSAT = '". $respuesta["timbrado"]["representacion_impresa_selloSAT"] . "',";
	$insert_facturas.=" representacion_impresa_certificadoSAT = '". $respuesta["timbrado"]["representacion_impresa_certificadoSAT"] . "',";
	$insert_facturas.=" url_pdf = '". 'timbrados/'.$rfc."_".$folio_facturas.'.pdf' . "',";
	$insert_facturas.=" timbrado = '$timbrado', ";
	$insert_facturas.=" observaciones = '$observaciones'";
	
	$result = mysqli_query($link, $insert_facturas);
	
	if($result){
		$respuesta["insert_facturas_estatus"]  = "success";
		$respuesta["insert_facturas_mensaje"]  = "Agregado a DB";
		$id_facturas =  mysqli_insert_id($link);
		$respuesta["id_facturas"]  = $id_facturas;
		$i_conceptos = 0;
		foreach($respuesta["datos_enviados"]["conceptos"] as $index=>$concepto){
			
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
			
			
			$i_conceptos++;
		}
	}
	else{
		$respuesta["insert_facturas_estatus"]  = "error";
		$respuesta["insert_facturas_mensaje"]  = mysqli_error($link);
	
	}
	
	$respuesta["insert_facturas"]  = $insert_facturas;
	
	
	//Actualiza id_pagos como facturado 
	if(isset($_POST["id_pagos"])){
		$update_pagos = "UPDATE pagos SET facturado = 1 WHERE id_pagos IN(".implode(",",$_POST["id_pagos"]).")";
		 
		$respuesta["update_pagos"]  = $update_pagos;
		
		$result = mysqli_query($link, $update_pagos);
		if($result){
			$respuesta["update_pagos_estatus"]  = "success";
			$respuesta["update_pagos_mensaje"]  = "Pagos facturados";
		
		}
		else{
			$respuesta["update_pagos_estatus"]  = "error";
			$respuesta["update_pagos_mensaje"]  = mysqli_error($link);
		
		}
	}
	
	
	//Actualiza Folios
	if($folio_facturas != ""){
		$folio_facturas++;
		$update_folios = "UPDATE emisores
				LEFT JOIN (
				SELECT
					id_emisores,
					folios_restantes_emisores - 1 AS folios_restantes
				FROM
					emisores
				WHERE
					id_emisores = '1'
			) AS tabla_folios_nuevos USING (id_emisores)
			SET serie_actual_emisores = '$folio_facturas',
			 folios_restantes_emisores = folios_restantes
			WHERE
				id_emisores = '$id_emisores'";
				
		
		$result = mysqli_query($link, $update_folios); 
		
		if($result){
			$respuesta["update_folios_estatus"]  = "success";
			$respuesta["update_folios_mensaje"]  = "Folios Actualizados";
		
		}
		else{
			$respuesta["update_folios_estatus"]  = "error";
			$respuesta["update_folios_mensaje"]  = mysqli_error($link);
		
		}
		$respuesta["update_folios"]  = $update_folios;
		$respuesta["folio_facturas"]  = $folio_facturas;
			
	}
	

}
else{
	
	$respuesta["datos_enviados"]  = $datos;
}


$respuesta["datos_recibidos"]  = $datos;
echo json_encode($respuesta);



