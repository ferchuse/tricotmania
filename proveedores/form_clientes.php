
<form id="form_edicion" autocomplete="off" class="was-validated">
	<div class="modal fade" id="modal_edicion" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content"> 
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Proveedores</h4>
				</div>
				<div class="modal-body">
					<form>
						<div class="form-group" hidden>
							<label for="id_proveedores">ID</label>
							<input style="margin:10px 0;" readonly type="text" class="form-control" id="id_proveedores" name="id_proveedores" placeholder="">
						</div>
						<div class="form-group">
							<label for="nombre_proveedor">Nombre Proveedor</label>
							<input required type="text" class="form-control" id="nombre_proveedores" name="nombre_proveedores" placeholder="">
						</div>
						<div class="form-group">
							<label for="nombre_proveedor">Telefono</label>
							<input  type="tel" class="form-control" id="telefono" name="telefono" placeholder="">
						</div>
						<div class="form-group">
							<label for="dias_credito">Dias de Crédito</label>
							<input   type="number" class="form-control" id="dias_credito" name="dias_credito" placeholder="">
						</div>
					</form> 
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
				</div>
			</div>
		</div>
	</div>
</form>