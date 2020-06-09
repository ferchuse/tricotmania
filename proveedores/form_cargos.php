<form id="form_cargos" autocomplete="off" class="is-validated">
	<div id="modal_cargos" class="modal fade" role="dialog">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title text-center">Nuevo <span id="titulo"></span></h3>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<div class="row">
						
						<input  type="hidden" id="cargos_id_proveedores" name="id_proveedores">
						<input  type="hidden" id="tipo" name="tipo">
						<div class="col-12">
							<div class="form-group">
								<label >Fecha:</label>
								<input required type="date" class="form-control" name="fecha" value="<?php echo date("Y-m-d")?>"> 
							</div>
							<div class="form-group">
								<label >Concepto:</label>
								<input required type="text" class="form-control" name="concepto" placeholder="">
							</div>
							<div class="form-group">
								<label for="">Importe:</label>
								<input required class="form-control" type="number" name="importe" id="importe">
							</div>
							<div class="form-group">
								<label for="">Saldo Anterior:</label>
								<input  readonly  class="form-control" type="number" name="saldo_anterior" id="saldo_anterior">
							</div>
							
							<div class="form-group">
								<label for="">Saldo Restante:</label>
								<input readonly class="form-control" type="number" name="saldo_restante" id="saldo_restante">
							</div>
							
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
					<button type="submit" class="btn btn-success" >
						<i class="fa fa-save"></i> Guardar
					</button>
				</div>
			</div>
		</div>
	</div>
</form>	