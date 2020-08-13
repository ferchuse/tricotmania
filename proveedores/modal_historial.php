<?php
	
	include("../conexi.php");
	$link = Conectarse();
	$lista_transacciones = [];


	$consulta = "
	SELECT
	id_compras AS id_transaccion,
	'CARGO' as tipo,
	'compras' AS tabla,
	fecha_compras AS fecha,
	CONCAT('Compra #', id_compras, '<br>', lista_conceptos) as concepto,
	total_compras AS importe,
	nombre_proveedores
	FROM
	compras
	LEFT JOIN proveedores USING(id_proveedores)
	LEFT JOIN (
	SELECT id_compras, 
	GROUP_CONCAT(descripcion SEPARATOR '<br>') AS lista_conceptos
	
	FROM compras_detalle
	GROUP BY id_compras
	
	) as t_conceptos 
	USING (id_compras)
	
	WHERE id_proveedores = '{$_GET["id_proveedores"]}'
	
	UNION
	
	SELECT
	id_abonos AS id_transaccion,
	'ABONO' as tipo,
	'abonos' as tabla,
	fecha,
	concepto,
	importe,
	nombre_proveedores
	FROM
	abonos
	LEFT JOIN proveedores USING(id_proveedores)
	WHERE id_proveedores = '{$_GET["id_proveedores"]}'
	
	UNION
	
	SELECT
	id_cargos AS id_transaccion,
	'CARGO' as tipo,
	'cargos' as tabla,
	fecha,
	concepto,
	importe,
	nombre_proveedores
	FROM
	cargos
	LEFT JOIN proveedores USING(id_proveedores)
	WHERE id_proveedores = '{$_GET["id_proveedores"]}'
	
	ORDER BY
	fecha 
	";
	
	
	$result = mysqli_query($link,$consulta) or die ("<pre>Error en $consulta". mysqli_error($link). "</pre>");
	
	while($fila = mysqli_fetch_assoc($result)){
		
		$lista_transacciones[] = $fila;
		
	}
?>
<pre hidden>
	<?= $consulta;?>
</pre>

<div id="modal_historial" class="modal fade " role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title text-center">Estado de Cuenta <span id="nombre_historial"></span></h3>
				<button type="button" class="close d-print-none" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<?php
					if(count($lista_transacciones) > 0){
					?>
					<div class="table-responsive">
						<table class="table table-hover ">
							<tr>
								<th class="text-center">Fecha</th>
								<th class="text-center">Concepto</th>
								<th class="text-center">Cargo</th>
								<th class="text-center">Abono</th>
								<th class="text-center">Saldo</th>
								<th class="text-center d-print-none">Acciones</th>
							</tr>
							<?php 
								$cargos= 0;
								$abonos= 0;
								$saldo= 0;
								foreach($lista_transacciones AS $i => $transaccion){
									
								?>
								<tr class="text-center">
									
									<td><?php echo date("d/m/Y", strtotime($transaccion["fecha"]));?></td>
									<td>
										<?php
											switch($transaccion["tabla"]){
												case "compras":
											?>
											<a target="_blank" href="../compras/imprimir_compras.php?id_compras=<?= $transaccion["id_transaccion"] ?>">
												<?php echo $transaccion["concepto"];?>
											</a>
											<?php
												break;
												case "cargos":
											?>
											<a target="_blank" href="imprimir_cargos.php?id_registro=<?= $transaccion["id_transaccion"] ?>">
												<?php echo $transaccion["concepto"];?>
											</a>
											<?php
												break;
												case "abonos":
											?>
											<a target="_blank" href="imprimir_abonos.php?id_registro=<?= $transaccion["id_transaccion"] ?>" class="text-success">
												<?php echo $transaccion["concepto"];?>
											</a>
											<?php
												break;
											}
										?>
										
									</td>
									
									<?php if($transaccion["tipo"] == "CARGO"){
										$cargos+=$transaccion["importe"];
										$saldo+=$transaccion["importe"];
									?>
									<td>$<?php echo number_format($transaccion["importe"],2);?></td>
									<td>-</td>
									
									<?php
									}
									else{
										$abonos+=$transaccion["importe"]; 
										$saldo-=$transaccion["importe"]; 
										
									?>
									
									<td>-</td>
									<td>$<?php echo number_format($transaccion["importe"],2);?></td>
									
									<?php	
									}
									?>
									
									<td>$<?php echo number_format($saldo,2);?></td>
									<td class="d-print-none">
										<button class="btn btn-danger btn_borrar_transaccion" 
										data-id_registro="<?php echo $transaccion["id_transaccion"]?>"
										data-tipo="<?php echo $transaccion["tipo"]?>"
										>
											<i class="fa fa-trash"></i>
										</button>
										
									</td>
									
								</tr>
								<?php
								}
							?>
							<tfoot class="h5 text-white bg-secondary">
								<tr class="text-center bg-secondary">
									<td>TOTALES:</td>
									<td></td>
									<td >$<?php echo number_format($cargos,2);?></td>
									<td>$<?php echo number_format($abonos,2);?></td>
									<td>$<?php echo number_format($saldo2,2);?></td>
									
								</tr>
							</tfoot>
						</table>
					</div>
					<?php
					}
					else{
						
						echo "<div class='alert alert-warning'>No hay Transacciones</div>";
					}
				?>
			</div>
			<div class="modal-footer d-print-none">
				<button type="button" class="btn btn-danger" data-dismiss="modal">
					<i class="fa fa-times"></i> Cerrar
				</button>
				<button hidden type="button" class="btn btn-info" onclick="window.print();">
					<i class="fa fa-print"></i> Imprimir
				</button>
			</div>
		</div>
	</div>
</div>	