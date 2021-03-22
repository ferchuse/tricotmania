<?php 
	session_start();
	include("../conexi.php");
	$link = Conectarse();
	
	$id_emisores = 1;
	
	
	$span_cancelado ="<span class='badge badge-danger'>CANCELADO</span>"; 
	$span_activo ="<span class='badge badge-success'>ACTIVO</span>"; 
	$span_timbrado ="<span class='badge badge-success'>SI</span>"; 
	$span_prueba ="<span class='badge badge-warning'>PRUEBA</span>"; 
	$span_cobrado ="<span class='badge badge-success'>SI</span>"; 
	$span_pendiente ="<span class='badge badge-danger'>NO</span>"; 
	$suma_subtotal = 0 ; 
	$suma_iva = 0 ; 
	$suma_total = 0; 
	
	$query ="SELECT * FROM facturas 
	LEFT JOIN emisores USING(id_emisores) 
	LEFT JOIN clientes USING(id_clientes)";
	
	
	if(isset($_GET['year_facturas'])){
		
		$query.=" WHERE YEAR(fecha_facturas) = '".$_GET['year_facturas']."' ";
		if($_GET['mes_facturas'] != ""){
			$query.=" AND MONTH(fecha_facturas) = '".$_GET['mes_facturas']."' ";
			
		}
		}elseif(isset($_GET['mes_facturas'])){
		if($_GET['mes_facturas'] != ""){
			$query.=" WHERE  MONTH(fecha_facturas) = '".$_GET['mes_facturas']."' ";
		}
		
	}
	
	if(isset($_GET['mostrar_timbrados'])){
		$query.=" AND  timbrado = '".$_GET['mostrar_timbrados']."' ";
	}
	
	$query.=" AND facturas.id_emisores = '$id_emisores' ";
	$query.=" ORDER BY  folio_facturas ";
	
	
	$result =mysqli_query($link,$query) or die("Error en: $query  ".mysqli_error($link));
	
	while($row = mysqli_fetch_assoc($result)){
		$cobrado = $row["cobrado"];
		$id_facturas = $row["id_facturas"];
		$folio_facturas = $row["folio_facturas"];
		$fecha_facturas = date("d/m/Y", strtotime($row["fecha_facturas"]));
		$razon_social_clientes = $row["razon_social_clientes"];
		$rfc_clientes = $row["rfc_clientes"];
		$correo_clientes = $row["correo_clientes"];
		$alias_clientes = $row["alias_clientes"];
		$url_pdf = $row["url_pdf"];
		$url_xml = $row["archivo_xml"];
		$subtotal = $row["subtotal"];
		$saldo_actual = $row["saldo_actual"];
		$metodo_pago = $row["metodo_pago"];
		$iva = $row["total_traslados"];
		$total = $row["total"];
		$cancelada = $row["cancelada"];
		$timbrado = $row["timbrado"];
		$motivo_cancelacion = $row["motivo_cancelacion"];
		
		if($cancelada != 1 && $timbrado == 1){
			$suma_subtotal+= $subtotal ; 
			$suma_iva+= $iva ; 
			$suma_total+= $total; 
			
		}
		
	?>
	<tr>
		<td class="text-center"><?php echo $folio_facturas; ?></td>
		<td class="text-center"><?php echo $fecha_facturas;?></td>
		<td class="text-center"><?php echo $razon_social_clientes;?></td>
		<td class="text-center"><?php echo number_format($subtotal,2); ?></td>
		<td class="text-center"><?php echo number_format($iva,2); ?></td>
		<<td class="text-center"><?php echo number_format($total,2); ?></td>
		<td class="text-center"><?php echo $cancelada == '1' ? $span_cancelado : $span_activo; ?></td>
		<td class="text-center"><?php echo $timbrado == '1' ? $span_timbrado : $span_prueba; ?></td>
		<td class="text-center hidden"><?php echo $cobrado == '1' ? $span_cobrado : $span_pendiente; ?></td>
		<td class="text-center hidden-print"> 
			<div class="btn-group">
				<a class="btn btn-default btn_vista <?php echo $cancelada == '1' ? "hidden" : ''; ?>" target="_blank" title="Vista Previa"  href="facturacion/vista_previa.php?id_facturas=<?= $id_facturas;?>">
					<i class="fa fa-eye" ></i>
				</a>
				<?php if($timbrado == 0){?>
					<button class="btn btn-danger btn_eliminar <?php echo $cancelada == '1' ? "hidden" : ''; ?>" type="button" title="Eliminar Factura" data-folio_facturas="<?php echo $folio_facturas; ?>" data-id_facturas="<?php echo $id_facturas; ?>">
						<i class="fa fa-trash" ></i>
					</button>
					<a class="btn btn-warning" href="facturas_editar.php?id_facturas=<?php echo $id_facturas;?>"  title="Copiar Factura" >
						<i class="fa fa-copy"></i>
					</a>
					<button class="btn btn-success btn_timbrar <?php echo $cancelada == '1' ? "hidden" : ''; ?>" type="button" title="Timbrar Factura" data-folio_facturas="<?php echo $folio_facturas; ?>" data-id_facturas="<?php echo $id_facturas; ?>">
						<i class="fa fa-certificate" ></i>
					</button>
					
					<a class="btn btn-info" target="_blank" type="button" title="Ver PDF" href="facturacion/<?php echo $url_pdf; ?>">
						<i class="fa fa-file-pdf-o"></i>
					</a> 
					<?php
						if($metodo_pago == "PPD"){ ?>
						<button class="btn btn-success btn_pago <?php echo $cancelada == '1' ? "hidden" : ''; ?>" 
						type="button" title="Registrar Pago" data-saldo_actual="<?php echo $saldo_actual; ?>" 
						data-id_facturas="<?php echo $id_facturas; ?>">
							<i class="fa fa-dollar" ></i>
						</button>
						<?php	
						}
					}
					else{
					?>
					<button class="btn btn-danger btn_cancelar <?php echo $cancelada == '1' ? "hidden" : ''; ?>" type="button" title="Cancelar Factura" data-uuid="<?= $row["uuid"]; ?>" data-id_facturas="<?php echo $id_facturas; ?>">
						<i class="fa fa-times" ></i>
					</button>
					<button class="btn btn-primary btn_correo" type="button" title="Enviar por Correo" data-correo="<?php echo $correo_clientes; ?>" data-url_xml="<?php echo $url_xml;?>" data-url_pdf="<?php echo $url_pdf;?>"> <i class="fa fa-envelope" ></i>
					</button>
					<a class="btn btn-info" target="_blank" type="button" title="Ver PDF" href="facturacion/<?php echo $url_pdf; ?>">
						<i class="fa fa-file-pdf-o"></i>
					</a> 
					<a class="btn btn-default" target="_blank" type="button" title="Ver XML" href="facturacion/<?php echo $url_xml; ?>">
						<i class="fa fa-qrcode"></i>
					</a>
					<a class="btn btn-warning" href="facturas_editar.php?id_facturas=<?php echo $id_facturas;?>"  title="Copiar Factura" >
						<i class="fa fa-copy"></i>
					</a>
					<?php
						if($metodo_pago == "PPD"){ ?>
						<button class="btn btn-success btn_pago <?php echo $cancelada == '1' ? "hidden" : ''; ?>" 
						type="button" title="Registrar Pago" data-saldo_actual="<?php echo $saldo_actual; ?>" 
						data-id_facturas="<?php echo $id_facturas; ?>">
							<i class="fa fa-dollar" ></i>
						</button>
						<?php	
						}
					}
				?>
			</div>
		</td>
	</tr>
	<?php
	}
?>

<tr>
	<td ></td>
	<td ></td>
	<td ></td>
	<td class="text-center">$<?php echo number_format($suma_subtotal, 2); ?></td>
	<td class="text-center">$<?php echo  number_format($suma_iva, 2);?></td>
	<td class="text-center">$<?php echo  number_format($suma_total, 2);?></td>
</tr>