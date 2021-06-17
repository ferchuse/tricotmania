<?php
	// include("login/login_success.php");
	include_once("control/is_selected.php");
	include_once("conexi.php");
	$link = Conectarse();
	$menu_activo = "facturas";
	
	$year = date("Y");
	$mes = date("n");
	
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Facturas</title>
		
		<?php include("styles.php");?>
		
	</head>
  <body>
		
		
		<?php include("menu.php");?>
		
		<h3 class="text-center">Facturas</h3>
		
		<div class="container-fluid"  > 
			
			<form class="form-inline hidden-print" id="form_filtros">
				<div class="form-group">
					<label for="id_ciclos" class="text-center">Año:</label>
					<select class="form-control filtro" id="year_facturas" name="year_facturas" >
						<option <?php echo is_selected("2017", $year);?> value="2017">2017</option>
						<option <?php echo is_selected("2018", $year);?> value="2018">2018</option>
						<option <?php echo is_selected("2019", $year);?> value="2019">2019</option>
						<option <?php echo is_selected("2020", $year);?> value="2020">2020</option>
						<option <?php echo is_selected("2021", $year);?> value="2021">2021</option>
					</select>
					</div>
				<div class="form-group">
					<label for="mes_facturas" class="text-center">Mes:</label>
					<select class="form-control filtro" id="mes_facturas" name="mes_facturas" >
						<option value="">Todos</option>
						<option <?php echo is_selected("1", $mes);?> value="1">ENERO</option>
						<option <?php echo is_selected("2", $mes);?> value="2">FEBRERO</option>
						<option <?php echo is_selected("3", $mes);?> value="3">MARZO</option>
						<option <?php echo is_selected("4", $mes);?> value="4">ABRIL</option>
						<option <?php echo is_selected("5", $mes);?> value="5">MAYO</option>
						<option <?php echo is_selected("6", $mes);?> value="6">JUNIO</option>
						<option <?php echo is_selected("7", $mes);?> value="7">JULIO</option>
						<option <?php echo is_selected("8", $mes);?> value="8">AGOSTO</option>
						<option <?php echo is_selected("9", $mes);?> value="9">SEPTIEMBRE</option>
						<option <?php echo is_selected("10", $mes);?> value="10">OCTUBRE</option>
						<option <?php echo is_selected("11", $mes);?> value="11">NOVIEMBRE</option>
						<option <?php echo is_selected("12", $mes);?> value="12">DICIEMBRE</option>
					</select>
				</div>
				
				
				<div class="form-group">
					<input class="form-control" type="search" id="buscar_cliente" placeholder="Buscar Cliente">	
				</div>
				<div class="checkbox">
					<label ><input type="checkbox"  class="filtro" value="1" name="mostrar_pruebas" id="mostrar_pruebas"> Mostrar Pruebas</label>
				</div>
				
			</form>
			<div class="pull-right">
				<a class="btn btn-success" href="facturas_nueva.php" >
					<i class="fa fa-plus" ></i> Nueva Factura
				</a>	
				<button class="btn btn-primary exportar">
					<i class="fa fa-arrow-right" ></i> Exportar
				</button>	
				<button class="btn btn-info" onclick="window.print()">
					<i class="fa fa-print" ></i> Imprimir
				</button>	
			</div>
			
		</div>
		<hr>
		<div class="container-fluid"  > 
			<div class="row">
				<div class="col-sm-12" >
					<div class="panel panel-primary" >
						<div class="panel-body "  >
							<div class="table-responsive">
								<table class="table table-bordered " id="tabla_reporte">
									<thead> 
										<tr>
											<th>Folio</th>
											<th>Fecha</th>
											<th>Razon Social</th>
											<th>Subtotal</th>
											<th>IVA</th>
											<th>Total</th>
											<th class="hidden-print">Estatus SAT</th>
											<th class="hidden-print">Timbrado </th>
											<th class="hidden-print">Cobrado </th>
											<th class="hidden-print">Acciones</th>
											</tr>
									</thead>
									<tbody id="lista_facturas"> 
										
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		
		<form id="form_correo" class="form" >
			<div id="modal_correo" class="modal fade" role="dialog">
				<div class="modal-dialog modal-sm"> 
					<!-- Modal content--> 
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title text-center"></h4>
						</div>
						
						<div class="modal-body">
							
							<div class="form-group">
								<label for="id_niveles">Correo:</label>
								<input  type="text" required name="correo" id="correo" class="form-control minus" >
								<input type="hidden" name="url_xml" id="url_xml" class="form-control" >
								<input type="hidden" name="url_pdf" id="url_pdf" class="form-control" >
								<input type="hidden" name="folio" id="correo_folio" class="form-control" >
								<input type="hidden" name="nombre" id="correo_nombre" class="form-control" >
							</div>
						</div>
						
						<div class="modal-footer">
							
							<button type="button" class="btn btn-danger" data-dismiss="modal">
								<i class="fa fa-times"></i> Cancelar
							</button>
							<button type="submit" class="btn btn-success">
								<i class="fa fa-envelope" ></i> Enviar
							</button>
							
						</div>
						
					</div>
				</div>
			</div>
		</form>
		
		
		<form id="form_pago" class="form" >
			<div id="modal_pago" class="modal fade" role="dialog">
				<div class="modal-dialog modal-sm"> 
					<!-- Modal content--> 
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title text-center">Complemento de Pago</h4>
						</div>
						
						<div class="modal-body">
							
							<div class="checkbox">
								<label for="id_niveles">
									<input  type="checkbox" name="modo_pruebas" id="modo_pruebas"  >
									Modo Pruebas 
								</label>
								
							</div>
							<div class="form-group hidden">
								<input  type="text" required name="id_facturas" id="id_facturas" class="form-control" >
							</div>
							<div class="form-group ">
								<label>Serie:</label>
								<input  type="text"  name="serie" id="serie" class="form-control" >
							</div>
							<div class="form-group ">
								<label>Folio:</label>
								<input  type="text"  name="folio" id="folio" class="form-control" >
							</div>
							<div class="form-group ">
								<label for="">Num Parcialidad:</label>
								<input  type="number" value="1" required name="num_parcialidad" id="num_parcialidad" class="form-control" >
							</div>
							<div class="form-group">
								<label for="id_niveles">Saldo Anterior:</label>
								<input  type="number" step="any"  readonly required name="saldo_anterior" id="saldo_anterior" class="form-control" >
							</div>
							<div class="form-group">
								<label for="id_niveles">Cantidad Pago:</label>
								<input  type="number" step="any"  required name="abono" id="abono" class="form-control" >
							</div>
							<div class="form-group">
								<label for="id_niveles">Saldo Restante:</label>
								<input  type="number" step="any" required name="saldo_restante" id="saldo_restante" class="form-control"  >
							</div>
							<div class="form-group">
								<label class="control-label" for="forma_pago">Forma de Pago:</label>
								<select id="forma_pago" name="forma_pago" class="form-control" >
									<option value="">Seleccione...</option>
									<option value="01" >01 Efectivo</option>
									<option value="02">02 Cheque nominativo</option>
									<option selected value="03" >03 Transferencia electrónica de fondos</option>
									<option value="04">04 Tarjeta de crédito</option>
									<option value="06">Dinero Electrónico</option>
									<option value="28" >28 Tarjeta de débito</option>
									<option value="29" >29 Tarjeta de servicios</option>
									<option  value="99" >99 Por definir</option>
								</select>
							</div>
							<div id="mensaje_error" class="alert alert-danger hidden">
								
							</div>
							<div id="mensaje_timbrado" class="alert alert-success hidden">
								Facturando <i class="fa fa-spinner fa-spin"></i>
							</div>
							<div id="mensaje_pdf" class="alert alert-success hidden">
								Generando PDF <i class="fa fa-spinner fa-spin"></i>
							</div>
							<div id="mensaje_correo" class="alert alert-success hidden">
								Enviando Correo <i class="fa fa-spinner fa-spin"></i>
							</div>
							<pre id="debug" hidden>
							</pre>
						</div>
						
						<div class="modal-footer">
							
							<button type="button" class="btn btn-danger" data-dismiss="modal">
								<i class="fa fa-times"></i> Cancelar
							</button>
							<button type="submit" class="btn btn-success">
								<i class="fa fa-save" ></i> Guardar
							</button>
							
						</div>
						
					</div>
				</div>
			</div>
		</form>
		
		<?php  include('scripts.php'); ?>
		<script src="js/facturas.js?v=<?= date("Ymdhi")?>"></script>
		
		
		
	</body>
</html>
