<?php
	// Se desactivan los mensajes de debug
	error_reporting(0);
	session_start();
	date_default_timezone_set('America/Mexico_City');
	include_once("../conexi.php");
	require_once 'sdk2.php';
	
	
	$link = Conectarse();
	$respuesta = array();
	$datos = array();
	$conceptos = array();
	
	$id_facturas = $_POST["id_facturas"];
	$rfc = $_SESSION["rfc_emisores"];
	$pass_timbrado = $_SESSION["password"];
	$clave_privada = $_SESSION["password"];
	$serie = $_POST["serie"];
	$folio = $_POST["folio"];
	$folio_facturas = $serie.$folio ;
	if($folio_facturas == ''){
		
		$folio_facturas = date("dmY_Hi");
	}
	
	$id_emisores = $_SESSION["id_usuarios"];
	$rfc_emisores = $_SESSION["rfc_emisores"];
	$razon_social_emisores=  $_SESSION["razon_social_emisores"];
	$regimen_emisores= $_SESSION["regimen_emisores"];
	
	
	//datos de la factura anterior
	$consulta_factura = "SELECT * FROM facturas LEFT JOIN clientes USING(id_clientes) WHERE id_facturas = '$id_facturas'";
	$respuesta["consulta_factura"] = $consulta_factura;
	$result_factura = mysqli_query($link, $consulta_factura);
	
	
	if($result_factura){
		
		while($fila_factura = mysqli_fetch_assoc($result_factura)){
			$id_clientes = $fila_factura["id_clientes"];
			$rfc_clientes =  $fila_factura["rfc_clientes"];
			$razon_social_clientes =  $fila_factura["razon_social_clientes"];
			
			$uuid_dr =$fila_factura["uuid"];
			$metodo_pago_dr =$fila_factura["metodo_pago"];
			
			$lugar_expedicion = $fila_factura["lugar_expedicion"];
			
		}
		
	}
	
	//datos del pago
	$saldo_anterior =  $_POST["saldo_anterior"];
	$abono =  $_POST["abono"];
	$saldo_restante = $_POST["saldo_restante"];
	$forma_pago = $_POST["forma_pago"];
	
	$observaciones = $_POST["observaciones"];
	$produccion = isset($_POST["modo_pruebas"])? "NO" : "SI";
	$timbrado = isset($_POST["modo_pruebas"])? 0 : 1;
	
	$datos['version_cfdi'] = '3.3';
	
	// SE ESPECIFICA EL COMPLEMENTO
	$datos['complemento'] = 'pagos10';
	$datos['validacion_local'] = 'NO';
	$datos['cfdi']='timbrados/'.$rfc."_".$folio_facturas.'.xml';
	
	$datos['xml_debug']='timbrados/sin_timbrar'.$rfc."_".$folio_facturas.'.xml';
	
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
	$datos['receptor']['UsoCFDI'] = 'P01'; 
	
	
	// Datos de la Factura
	
	// Datos de la Factura
	$datos['factura']['fecha_expedicion'] = date('Y-m-d\TH:i:s', time() - 120);
	$datos['factura']['serie'] = $serie;
	$datos['factura']['folio'] = $folio;	
	$datos['factura']['moneda'] = 'XXX';
	$datos['factura']['subtotal'] = '0';
	$datos['factura']['total'] = '0';
	$datos['factura']['LugarExpedicion'] = $lugar_expedicion; 
	$datos['factura']['tipocomprobante'] = 'P';
	
	
	// Se agregan los conceptos
	//$datos['conceptos'][0]['unidad'] = 'ACT';
	$datos['conceptos'][0]['cantidad'] = '1';
	$datos['conceptos'][0]['ClaveProdServ'] = '84111506';
	$datos['conceptos'][0]['ClaveUnidad'] = 'ACT';
	$datos['conceptos'][0]['descripcion'] = "Pago";
	$datos['conceptos'][0]['valorunitario'] = '0.0';
	$datos['conceptos'][0]['importe'] = '0.0';
	
	
	
	// Complemento de Pagos 1.0
	$datos['pagos10']['Pagos'][0]['DoctoRelacionado'][0]['IdDocumento'] = $uuid_dr;
	$datos['pagos10']['Pagos'][0]['DoctoRelacionado'][0]['MonedaDR'] = 'MXN';
	$datos['pagos10']['Pagos'][0]['DoctoRelacionado'][0]['MetodoDePagoDR'] = $metodo_pago_dr;
	$datos['pagos10']['Pagos'][0]['DoctoRelacionado'][0]['NumParcialidad'] = '1';
	$datos['pagos10']['Pagos'][0]['DoctoRelacionado'][0]['ImpSaldoAnt']= $saldo_anterior;
	$datos['pagos10']['Pagos'][0]['DoctoRelacionado'][0]['ImpPagado'] = $abono;
	$datos['pagos10']['Pagos'][0]['DoctoRelacionado'][0]['ImpSaldoInsoluto'] = $saldo_restante;
	$datos['pagos10']['Pagos'][0]['FechaPago']= date('Y-m-d\TH:i:s', time() - 120);
	$datos['pagos10']['Pagos'][0]['FormaDePagoP']= $forma_pago;
	$datos['pagos10']['Pagos'][0]['MonedaP']= 'MXN';
	$datos['pagos10']['Pagos'][0]['Monto']= $abono;
	
	
	
	
	
	
	
	// Complemento de Pagos 1.0
	// $datos['pagos10']['Pagos'][0]['DoctoRelacionado'][0]['IdDocumento'] = '970e4f32-0fe0-11e7-93ae-92361f002671';
	// $datos['pagos10']['Pagos'][0]['DoctoRelacionado'][0]['MonedaDR'] = 'MXN';
	// $datos['pagos10']['Pagos'][0]['DoctoRelacionado'][0]['MetodoDePagoDR'] = 'PPD';
	// $datos['pagos10']['Pagos'][0]['DoctoRelacionado'][0]['NumParcialidad'] = '1';
	// $datos['pagos10']['Pagos'][0]['DoctoRelacionado'][0]['ImpSaldoAnt']= '10000';
	// $datos['pagos10']['Pagos'][0]['DoctoRelacionado'][0]['ImpPagado'] = '5000';
	// $datos['pagos10']['Pagos'][0]['DoctoRelacionado'][0]['ImpSaldoInsoluto'] = '5000';
	// $datos['pagos10']['Pagos'][0]['FechaPago']= date('Y-m-d\TH:i:s', time() - 120);
	// $datos['pagos10']['Pagos'][0]['FormaDePagoP']= '06';
	// $datos['pagos10']['Pagos'][0]['MonedaP']= 'MXN';
	// $datos['pagos10']['Pagos'][0]['Monto']= '10000';
	
	
	//$datos['pagos10']['Pagos'][0]['NumOperacion']= '0.0';
	// $datos['pagos10']['Pagos'][0]['RfcEmisorCtaOrd']= 'XAXX010101000';
	// $datos['pagos10']['Pagos'][0]['NomBancoOrdExt']= '0.0';
	// $datos['pagos10']['Pagos'][0]['CtaOrdenante']= '1234567890';
	//$datos['pagos10']['Pagos'][0]['RfcEmisorCtaBen']= '0.0';
	//$datos['pagos10']['Pagos'][0]['CtaBeneficiario']= '0.0';
	//$datos['pagos10']['Pagos'][0]['TipoCadPago']= '0.0';
	//$datos['pagos10']['Pagos'][0]['CertPago']= '0.0';
	//$datos['pagos10']['Pagos'][0]['CadPago']= '0.0';
	//$datos['pagos10']['Pagos'][0]['SelloPago']= '0.0';
	
	
	
	// Se ejecuta el SDK
	$respuesta["datos"]= $datos;
	$respuesta["timbrado"]= mf_genera_cfdi($datos);
	
	
	if($respuesta["timbrado"]["codigo_mf_numero"] == 0){
		
		//actualizar saldo de factura
		
		$insert_facturas =" INSERT INTO facturas SET ";
		$insert_facturas.=" folio_facturas = '". $folio_facturas . "',";
		$insert_facturas.=" id_emisores = '". $id_emisores . "',";
		$insert_facturas.=" fecha_facturas = CURDATE(),";
		$insert_facturas.=" id_clientes = '". $id_clientes . "',";
		$insert_facturas.=" metodo_pago = '". $metodo_pago . "',";
		$insert_facturas.=" forma_pago = '". $forma_pago . "',";
		$insert_facturas.=" lugar_expedicion = '". $lugar_expedicion . "',";
		$insert_facturas.=" subtotal = '". $subtotal . "',";
		$insert_facturas.=" saldo_actual = 0,";
		$insert_facturas.=" total = '". $total . "',";
		$insert_facturas.=" tipo_comprobante = 'P',";
		$insert_facturas.=" uso_cfdi = 'P01',";
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
		
		$respuesta["result_factura"] = mysqli_query($link, $insert_facturas);
		$respuesta["id_factura_nueva"] = mysqli_insert_id($link);
		
		
		$insert_pagos = "INSERT INTO pagos SET
		id_facturas = '{$respuesta["id_factura_nueva"]}',
		fecha_pago = CURDATE(),
		moneda_pago = 'MXN',
		importe_pagado = '$abono',
		forma_pago = '$forma_pago',
		num_parcialidad = '{$_POST['num_parcialidad']}',
		saldo_anterior = '$saldo_anterior',
		saldo_restante = '$saldo_restante',
		uuid_dr = '$uuid_dr',
		metodo_pago_dr = '$metodo_pago_dr'
		
		";
		if(mysqli_query($link, $insert_pagos)){
			
			$respuesta["result_pagos"] = "OK";
			
			
			//Actualiza Folios
			if($folio_facturas != ""){
				$folio_facturas++;
				$update_folios = "UPDATE emisores
				
				SET serie_actual_emisores = '$folio_facturas',
				folios_restantes_emisores = folios_restantes_emisores - 1
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
			$respuesta["result_pagos"] = mysqli_error($link);
			
		}
		
		//Actualiza Saldo Actual de DR
		$update_saldo = "UPDATE facturas SET saldo_actual = $saldo_restante WHERE id_facturas = '$id_facturas'";
		
		if(mysqli_query($link, $update_saldo)){
			
			$respuesta["update_saldo"] = "OK";
			
		}
		
		else{
			$respuesta["update_saldo"] = mysqli_error($link);
			
		}
		
	}
	
	
	
	echo json_encode($respuesta);
	
	
	
	
?>