<?php
	
	include("../conexi.php");
	$link = Conectarse();
	
	
	$consulta = "SELECT * FROM abonos
	LEFT JOIN proeedores USING (id_proveedores)
	WHERE id_abonos={$_GET["id_registro"]}";
	
	$result = mysqli_query($link, $consulta);
	
	if(!$result){
		die(mysqli_error($link));
	}
	
	
	while ($fila = mysqli_fetch_assoc($result)) {
		$abono = $fila;
	}
	
	
	$nombre_empresa= "TRICOTMANIA";
	
?>


<!DOCTYPE html>
<html lang="es">
	
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		
		<title>Nota de Remisión</title>
		
		
		<?php include("../styles.php"); ?>
		<link rel="stylesheet" href="imprimir_movimiento.css">
	</head>
	
	<body>
		<div class="container h4">
			
			<section class="mt-3 ">
				<div class="row">
					
					
					<div class="col-5">
							<img class="img-fluid" src="../img/logo.png" alt="">
						<form id="form_cargos" autocomplete="off" class="is-validated">
							
							<h3 class="modal-title text-center">Nuevo <span id="titulo"></span></h3>
						
							<div class="row">
								
								<input  type="hidden" id="cargos_id_clientes" name="id_clientes">
								<input  type="hidden" id="tipo" name="tipo">
								<div class="col-12">
									<div class="form-group">
										<label >Fecha:</label>
										<input required type="date" class="form-control" name="fecha" value="<?= $abono["fecha"]?>"> 
									</div>
									<div class="form-group">
										<label >Concepto:</label>
										<input required type="text" class="form-control" name="concepto" placeholder="Abono" value="<?= $abono["concepto"]?>">
									</div>
									<div class="form-group">
										<label for="">Importe:</label>
										<input required class="form-control" type="number" name="importe" id="importe" value="<?= $abono["importe"]?>">
									</div>
									<div class="form-group">
										<label for="">Saldo Anterior:</label>
										<input  readonly  class="form-control" type="number" name="saldo_anterior" id="saldo_anterior" value="<?= $abono["saldo_anterior"]?>">
									</div>
									
									<div class="form-group">
										<label for="">Saldo Restante:</label>
										<input readonly class="form-control" type="number" name="saldo_restante" id="saldo_restante" value="<?= $abono["saldo_restante"]?>">
									</div>
									
								</div>
							</div>
							
							<div class="modal-footer">
								<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
								<button type="submit" class="btn btn-success" >
									<i class="fa fa-save"></i> Guardar
								</button>
							</div>
						</form>	
					</div>
					
					
					
				</div>
				
				
			</section>
			
		
			<pre hidden>
				<?php print_r($filas)?>
			</pre>
			
		</div>
	</body>

</html>							