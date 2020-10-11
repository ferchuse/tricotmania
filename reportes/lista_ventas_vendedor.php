<?php 
	include('../conexi.php');
	$link = Conectarse();
	
	
	$consultaVentas = "
	SELECT
	*
	FROM
	usuarios
	LEFT JOIN
	(
	SELECT
	id_usuarios,
	COALESCE (SUM(total_ventas), 0) AS ventas_vendedor,
	COALESCE (COUNT(*), 0) AS num_ventas
	FROM
	ventas
	WHERE
	estatus_ventas <> 'CANCELADO'
	AND fecha_ventas BETWEEN '{$_GET["fecha_inicio"]}'
	AND '{$_GET["fecha_fin"]}'
	GROUP BY
	id_usuarios
	) as t_ventas_vendedor
	USING (id_usuarios)
	
	
	
	";
	
	$resultadoVentas = mysqli_query($link,$consultaVentas);
	
	
	
?>
<pre hidden>
	<?php echo $consultaVentas;?>
</pre>

<?php 
	if(mysqli_num_rows($resultadoVentas) < 1){
	?>
	<br>
	<br>
	<div class="alert alert-warning text-center">
		<strong>No hay ventas en estas fechas</strong> 
	</div>
	<?php		
	}
	else{
	?>
	
	<div class="col-sm-12">
		<div class="panel panel-primary">
			<div class="panel-heading hidden-print">
				<h4 class="text-center"> 
					Ventas por Vendedor
					<button type="button" onclick="window.print();" class="btn btn-info pull-right" >
						<i class="fas fa-print"></i> Imprimir
					</button>
				</h4>
			</div>
			<div class="panel-body" id="panel_ingresos">
				<div class="table-responsive">
					
					<table class="table table-hover">
						<tr>
							<th class="text-center"> Usuario</th>
							<th class="text-right"> Num. Ventas</th>
							<th class="text-right"> Importe Vendido</th>
						</tr>
						<?php
							$total_ventas = 0;
							$total_ganancia = 0;
							$total_num_ventas = 0;
							
							while($row_ventas = mysqli_fetch_assoc($resultadoVentas)){
								extract($row_ventas);
								$total_ventas+= $ventas_vendedor;
								$total_num_ventas+= $num_ventas;
							?>
							<tr>
								<td class="text-center">
									<?php echo $row_ventas["nombre_usuarios"];?>
								</td>
								<td class="text-right">
									<?= number_format($num_ventas);?>
								</td>
								<td class="text-right">
									
									<?php echo "$".number_format($ventas_vendedor,2);?>
								</td>
								</tr>
								
								<?php
								}
							?>
							
							<tfoot>
								<tr class="bg-info">
									<td class="text-danger">
										<big><b>TOTAL:</b></big>
									</td>
									<td class="text-right"><?= number_format($total_num_ventas);?>
										<td class="text-right">$<?=number_format($total_ventas,2);?>
										</td>
										
									</tr>
								</tfoot>
							</table> 
							
						</div>
					</div>
				</div>
			</div>
			
			<?php
			}
		?>					