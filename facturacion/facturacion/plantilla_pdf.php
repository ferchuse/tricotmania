<?php
	include("../conexi.php");
	$link = Conectarse();
	
	if(isset($_POST["id_facturas"])){
		
		$id_facturas = $_POST["id_facturas"];
		
		error_reporting(0);
		
	}
	else{
		$id_facturas = "1";
	}
	
	
	//Buscar Conceptos
	$consulta_detalle	= "SELECT * FROM facturas_detalle
	WHERE id_facturas = '$id_facturas'";
	
	$result = mysqli_query($link, $consulta_detalle);
	if($result){
		while($fila = mysqli_fetch_assoc($result)){	
			$conceptos[] = $fila;
		}
	}
	else{
		die("Error al generar Conceptos").mysqli_error($link);
	}
	
	//Buscar Conceptos
	$consulta_pagos	= "SELECT * FROM pagos
	WHERE id_facturas = '$id_facturas'";
	
	$result = mysqli_query($link, $consulta_pagos);
	if($result){
		while($fila = mysqli_fetch_assoc($result)){	
			$pagos[] = $fila;
		}
	}
	else{
		die("Error al cargar Pagos").mysqli_error($link);
	}
	
	
	$cat_tipo_comprobante =  ["I"=>"Ingreso", "P" => "Pago", "E"=> "Egreso"];
	$cat_uso_cfdi =  array("G03"=>"Gastos En General","P01"=>"Por Definir" );
	$cat_metodo_pago =  array("PUE"=>"Pago en una sola exhibición" , "PPD"=>"Pago en parcialidades o diferido");
	$cat_forma_pago = array("01"=> "Efectivo", 
	"02"=>	"Cheque nominativo",
	"03"=>	"Transferencia electrónica de fondos",
	"31"=>	"Intermediario de Pagos",
	"99"=>	"Por definir");
	
	
	if($_POST["orden_pago"] == 1){
		
		
		$orden_pago = "hidden";
	}
	else{
		
		$orden_pago = " ";
	}
?>

<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Factura</title>
    <link rel="stylesheet" href="https://phptopdf.com/bootstrap.css">
    <style>
			body { 
			font-family: DejaVu Sans, sans-serif; 
			font-size: 11px;
			}
			@page { margin: 10px; }
			.header_datos{
			padding-top: 10px;
			}
			
			.small{
			font-size: 9px;
			overflow-wrap: break-word !important;
			word-wrap: break-word !important;
			max-width: 18cm;
			}
			.tiny{
			font-size: 6px;
			overflow-wrap: break-word !important;
			word-wrap: break-word !important;
			max-width: 18cm;
			}
			@media print{
			body{
			font-size: 11px;
			}
			
			}
		</style>
	</head>
  
  <body>
    <div class="container-fluid">
			
			<?php if($_POST["orden_pago"] == 1){ ?>
				<center><h4>ORDEN DE PAGO</h4></center>
				
				<?php
				}	
			?>
      <div class="row">
        <div class="col-xs-2">
					<?php 
						if($_POST["url_logo"] != ''){
							echo "<img src='".$_POST["url_logo"]."' class='img-responsive'>";
						} 
					?>
					
				</div> 
				<div class="header_datos">
					<div class="col-xs-4">
						<h6>Emisor: </h6> <a href="#"><?php echo $_POST["razon_social_emisores"];?></a> <br>
						<p>
							RFC: <?php echo $_POST["rfc_emisores"];?>  <br>
							Régimen:  <?php echo $_POST["regimen_emisores"];?><br>
							Certificado: <?php echo $_POST["representacion_impresa_certificado_no"];?><br>
						</p>
					</div>
					<div class="col-xs-4 text-right">
						Folio: <span class="text-danger"><?php echo $_POST["folio_facturas"];?></span><br>
						Fecha: <?php echo date("d/m/Y", strtotime($_POST["fecha_facturas"]));?><br>
						<?php if($_POST["orden_pago"] == 0){ ?>
							Folio SAT: <?php echo $_POST["uuid"] ;?> <br>
							Fecha Certificación: <?php echo $_POST["representacion_impresa_fecha_timbrado"];?> <br>
							Certificado SAT: <?php echo $_POST["representacion_impresa_certificadoSAT"];?> <br>
							<?php 
							}
						?>
					</div>
				</div>
			</div>
      <div class="row">
				
        <div class="col-xs-5  ">
          <div class="panel panel-default">
            <div class="panel-heading">
							Receptor:
						</div>
            <div class="panel-body">
              <p>
								<b>Nombre: </b> <?php echo $_POST["razon_social_clientes"];?> <br>
               	<b> RFC: </b> <?php echo $_POST["rfc_clientes"];?> <br>
                
								<?php if($_POST["rfc_clientes"] == 'IMS421231I45'){
									
									echo "<b>Dirección: </b>Avenida Paseo de la Reforma No. 476, Colonia Juárez, </br> C.P. 06600, Delegación Cuauhtémoc, Ciudad de México.  ";
								}
								
								?> 
								<br>
								<b>USO CFDI: </b><?php echo $_POST["uso_cfdi"];?> <br>
							</p>
						</div>
					</div>
				</div>
				<div class="col-xs-5 ">
          <div class="panel panel-default">
            <div class="panel-heading">
              Datos de Pago:
						</div>
            <div class="panel-body">
              
							Tipo de Comprobante: <?php echo $_POST["tipo_comprobante"]."-".$cat_tipo_comprobante[$_POST["tipo_comprobante"]];?> <br>
							Lugar de Expedición: <?php echo $_POST["lugar_expedicion"];?><br>
							Forma de Pago: <?php echo $_POST["forma_pago"]."-".$cat_forma_pago[$_POST["forma_pago"]];?><br>
							Método de Pago:<?php echo $_POST["metodo_pago"]."-".$cat_metodo_pago[$_POST["metodo_pago"]];?>
						</div>
					</div>
				</div>
			</div>
      <!-- / Datos factura y receptor -->
			
			
			<div class="">
				<table border="1" class="table table-condensed">
					<tr class="text-center">
						<th>
							Cantidad
						</th>
						<th>
							Unidad 
						</th>
						<th>
							Descripción
						</th>
						<th>
							Precio
						</th>
						<th>
							Importe
						</th>
					</tr>
					<?php 
						foreach($conceptos as $index => $concepto){?>
						<tr >
							<td class="col-xs-1 text-center">
								<?php echo $concepto["cantidad"];?>
							</td>
							<td class="col-xs-1 text-center">
								Clave: <?php echo $concepto["unidad"];?>
							</td>
							<td class="col-xs-4">
								<?php echo nl2br($concepto["descripcion"]);?>
							</td>
							<td class="col-xs-1  ">$<?php echo $concepto["precio"];?></td>
							<td class="col-xs-1">$<?php echo $concepto["importe"];?></td>
						</tr>
						
						<?php 
							
						}
					?>
					
					<tfoot>
						
					</tfoot>
				</table>
			</div>
			<?php 
				
				// IF(count($conceptos) == 7){
				
				// echo "<div style='page-break-after: always;'></div>";
				// }
			?>
			<div class="row ">
				
        <div class="col-xs-4 ">
					Observaciones: 
					<?php echo $_POST["observaciones"]?> <br>
						<img style="max-width:150px;" alt="QR" src="<?php echo $_POST["archivo_png"];?>">
				</div>
				<div class="col-xs-2  text-right">
          <p>
            <strong>
							Subtotal : <br>
							Descuento :  <br>
							Traslados IVA: <br>
							Retención IVA : <br>
							Retención ISR : <br>
							Total : <br>
						</strong>
					</p>
				</div>
        <div class="col-xs-2 text-right">
          <strong>
						$ <?php echo number_format($_POST["subtotal"],2);?> <br>
						$ <?php echo number_format($_POST["descuento"],2);?> <br>
						$ <?php echo number_format($_POST["total_traslados"],2);?> <br>
						$ <?php echo number_format($_POST["retenciones_iva"],2);?> <br>
						$ <?php echo number_format($_POST["retenciones_isr"],2);?> <br>
						$ <?php echo number_format($_POST["total"],2);?> <br>
						<br>
					</strong>
				</div>
			</div>
			<?php if(count($pagos) > 0){ ?>
				<table class="table table-bordered table-condensed">
					<caption>Complemento de Pago</caption>
					<thead>
						<tr>
							<th>
								Factura Relacionada
							</th>
							<th>
								Fecha
							</th>
							<th>
								Forma de Pago
							</th>
							<th>
								Parcialidad
							</th>
							<th>
								Saldo Anterior
							</th>
							<th>
								Importe Pagado
							</th>
							<th>
								Saldo Restante
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php echo $pagos[0]["uuid_dr"]?></td>
							<td><?php echo $pagos[0]["fecha_pago"]?></td>
							<td><?php echo $cat_forma_pago[$pagos[0]["forma_pago"]]?></td>
							<td><?php echo $pagos[0]["num_parcialidad"]?></td>
							<td><?php echo $pagos[0]["saldo_anterior"]?></td>
							<td><?php echo $pagos[0]["importe_pagado"]?></td>
							<td><?php echo $pagos[0]["saldo_restante"]?></td>
						</tr>
					</tbody>
				</table>
				
				
				<?php
				}
			?>
			
			
			<footer <?php if(count($conceptos)  < 7 ) echo " class='footer'";?> >
				<div class="lead text-center <?php echo $_POST["timbrado"] == "1" ? "hidden" : "" ?>">
					<?php
						if($_POST["orden_pago"] == 1){
							echo "ORDEN DE PAGO";
						}
						else{
							//echo "ORDEN DE PAGO";
						}
					?>
					
				</div>
				
				<div class="row <?php echo $_POST["timbrado"] == "0" ? "" : "" ?>">
					
					<div class="col-xs-10">
						Sello Digital CFDI: <div class="tiny" ><?php echo $_POST["representacion_impresa_sello"];?></div>
						Sello SAT : <div class="tiny" > <?php echo $_POST["representacion_impresa_selloSAT"];?></div>
						Cadena original del complemento de certificación digital del SAT:
						<div class="tiny" > 
							<?php echo $_POST["representacion_impresa_cadena"];?> 
						</div>
					</div>
					
					
					<h6 class="text-center tiny">Este documento es una representación impresa de un CFDI</h6>
				</div>
				<pre hidden>
					<?php //echo var_dump($_POST);?>
				</pre>
			</footer>
			
		</div>
	</body>
</html>