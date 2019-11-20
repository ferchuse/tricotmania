<form id="form_pago">
	<div id="modal_pago" class="modal " role="dialog">
		
		<div class="modal-dialog ">
			
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-center">Datos de Pago</h4>
				</div>
				
				<div class="modal-body">
					<!-- "Pestañas" -->
					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#ventana_efectivo">Efectivo</a></li>
						<li class=""><a data-toggle="tab" href="#ventana_tarjeta">Tarjeta</a></li>
					</ul>
					
					<!-- "Ventanas" -->
					<div class="tab-content mt-4">
						<!-- "Ventana: Efectivo" -->
						<div id="ventana_efectivo" class="tab-pane fade in active">
							
							<div class="row">
								<div class="col-sm-10">
									<input  id="total_pago" value="0" type="hidden" class="valor form-control" name="total_pago">
								
									
									
									
									<div class="efectivo form-group">
										<div class="col-sm-6 text-right">
											<label> Total:</label>
										</div>
										<div class="col-sm-4">
											<input readonly id="efectivo" value="0" type="number" class="valor form-control text-right" name="efectivo">
										</div>
									</div>
									
									
									<div class="pago form-group">
										<div class="col-sm-6 text-right">
											<label>Se Recibe: </label>
										</div>
										<div class="col-sm-4">
											<input id="pago" step=".5" type="number" class="valor form-control text-right" name="pago">
										</div>
									</div>
									
									
									<div class="form-group mb-3"  style="margin-bottom:15px;" >
										<div class="col-sm-6 text-right">
											<label>Cambio: </label>
										</div>
										<div class="col-sm-4">
											<input readonly id="cambio" value="0" type="number" class="valor form-control text-right" name="cambio">
										</div>
									</div>
									
										
									<div class="form-group mb-3">
										<div class="col-sm-6 text-right">
											<label> Vendedor:</label>
										</div>
										<div class="col-sm-4">
											<?= generar_select($link, "usuarios", "id_usuarios", "nombre_usuarios", false, false, true);?>
										</div>
									</div>
									
								</div>
							</div>
						</div>
						
						<!-- "Ventana: Tarjeta" -->
						<div id="ventana_tarjeta" class="tarjeta tab-pane fade">
							<!-- "Total" -->
							<div class="total row">
								<div class="col-sm-12">
									<input readonly id="tarjeta" value="0" type="number" class="valor form-control" name="tarjeta">
								</div>
							</div>
							
							<!-- "Radios: Débito & Crédito" -->
							<div class="radios_tarjeta mt-4">
								<div class="form-check form-check-inline">
									<input class="form-check-input" value="1.02" type="radio" name="tarjeta" id="debito">
									<label class="form-check-label" for="debito">Débito</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input"  value="1.03" type="radio" name="tarjeta" id="credito">
										<label class="form-check-label" for="credito">Crédito</label>
									</div>
								</div>
								
								<!-- "Porcentaje Comisión" -->
								<div class="porcentaje row mt-4">
									<div class="col-sm-6">
										<label>% Comisión: </label>
									</div>
									<div class="col-sm-6">
										<input readonly id="porcentaje" value="0" type="number" class="valor form-control" name="porcentaje">
									</div>
								</div>
								
								<!-- "Comisión" -->
								<div class="comision row mt-2">
									<div class="col-sm-6">
										<label>Comisión: </label>
									</div>
									<div class="col-sm-6">
										<input readonly id="comision" value="0" type="number" class="valor form-control" name="comision">
									</div>
								</div>
								</div>
							</div>
						</div>
						
						
						<div class="modal-footer" >
							<button type="button" class="btn btn-danger" data-dismiss="modal">
								<i class="fa fa-times"></i> Cancelar
							</button>
							<button type="button" id="imprimir" class="btn btn-info">
								<i class="fa fa-print"></i> Cobrar e Imprimir
							</button>
							<button type="submit" id="cobrar" class="btn btn-success">
								<i class="fa fa-dollar-sign"></i> Solo Cobrar
							</button>
						</div>
						
					</div>
				</div>
				
			</div>
		</form>		