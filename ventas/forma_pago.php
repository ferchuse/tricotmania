<form id="form_pago" class="was-validated">
	<div id="modal_pago" class="modal" role="dialog">
		
		<div class="modal-dialog ">
			
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-center">Datos de Pago</h4>
				</div>
				
				<div class="modal-body">
					
					<div class="row">
						<div class="col-sm-12">
							<input  id="total_pago" value="0" type="hidden" class="valor form-control" name="total_pago">
							
							
							
							
							<div class="form-group">
								<div class="col-sm-6 text-right">
									<b>	Forma de Pago </b> <br>
								</div>
								<div class="col-sm-4">
									<label><INPUT TYPE="radio" class="forma_pago" name="forma_pago" value="efectivo" checked> Efectivo </label><br> 
									<label><INPUT TYPE="radio" class="forma_pago" name="forma_pago" value="tarjeta"> Tarjeta</label>
									
								</div>
							</div>
							
							
							<div class="form-group">
								<div class="col-sm-6 text-right">
									<label> Total:</label>
								</div>
								<div class="col-sm-4">
									<input readonly id="efectivo" value="0" type="number" class="valor form-control text-right" name="efectivo">
								</div>
							</div>
							
							
							
							
							<div class="form-group mb-3" >
								<div class="col-sm-6 text-right">
									<label> Vendedor:</label>
									</div>
									<div class="col-sm-4">
										<?= generar_select($link, "usuarios", "id_usuarios", "nombre_usuarios", false, false, true,0 , 0, "id_vendedores", "id_vendedores");?>
									</div>
								</div>
								
								</div>
							</div>
						</div>
						
						
						<div class="modal-footer" >
							<button type="button" class="btn btn-danger" data-dismiss="modal">
								<i class="fa fa-times"></i> Cancelar
							</button>
							<button type="submit" id="imprimir" class="btn btn-info">
								<i class="fa fa-print"></i> Cobrar
							</button>
							
						</div>
						
					</div>
				</div>
				
			</div>
		</form>				