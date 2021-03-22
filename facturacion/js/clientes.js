var filtros = {};
function buscarCliente(event) {
	var value = $(this).val().toLowerCase();
	$("#cuerpo tr").each(function() {
		$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
	});
	contarRegistros();
}


$(document).ready(function(){
	
	cargarTabla(filtros);
	
	$("#buscar_cliente").on("keyup", buscarCliente);
	
	$("#btn_insert").click(function(){
		
		var $formulario = $('#form_edit');
		$formulario[0].reset();
		$formulario.find(".modal").modal("show");
		$formulario.find(".action").val("insert");
	});
	
	
	$('#form_edit').submit( function guardarClientes(event){
		event.preventDefault();
		var $formulario = $(this);
		var action = $formulario.find(".action").val();
		var $modal = $formulario.find(".modal");
		var boton = $formulario.find(':submit');
		var icono = boton.find('.fa');
		icono.toggleClass('fa-save fa-spinner fa-spin ');
		boton.prop('disabled',true);
		
		if(action == "insert"){
			$.ajax({
				url: 'control/fila_insert.php',
				method: 'POST',
				data: {
					"tabla": "clientes",
					"valores": $formulario.serializeArray()
				}
				}).done(function(respuesta){
				if(respuesta["estatus"] == "success"){
					cargarTabla(filtros);
					$modal.modal("hide");				
					}else{
					alertify.error('Error' + respuesta["mensaje"]);
					
				}
				}).fail(function(xhr, error, errnum){
				alertify.error("Error" + error);
				}).always(function(){
				icono.toggleClass('fa-save fa-spinner fa-spin ');
				boton.prop('disabled',false);
				$formulario[0].reset();
			});
			
		}
		else{
			$.ajax({
				url: 'control/fila_update.php',
				method: 'POST',
				data: {
					"tabla": "clientes",
					"valores": $formulario.serializeArray(),
					"id_campo": "id_clientes",
					"id_valor": $("#id_clientes").val()
				}
				}).done(function(respuesta){
				if(respuesta["estatus"] == "success"){
					cargarTabla(filtros);
					$modal.modal("hide");				
					}else{
					alertify.error('Error' + respuesta["mensaje"]);
					
				}
				}).fail(function(xhr, error, errnum){
				alertify.error("Error" + error);
				}).always(function(){
				icono.toggleClass('fa-save fa-spinner fa-spin ');
				boton.prop('disabled',false);
				$formulario[0].reset();
			});
			
		}
		
		
	});
});



function contarRegistros(){
	$("#total_clientes").html($('#cuerpo tr').length);
	
}
function cargarTabla(filtros) {
	// var cargador = "<tr><td class='text-center' colspan='5'><i class='fa fa-spinner fa-spin fa-3x'></i></td></tr>";
	// $('#cuerpo').html(cargador);
	$.ajax({
		url: 'control/lista_clientes.php',
		method: 'GET',
		data: filtros
		}).done(function(respuesta) {
		$('#cuerpo').html(respuesta);
		
		contarRegistros();
		
		
		
		$('.btn_eliminar').click(function() {
			var boton = $(this);
			boton.prop('disabled', true);
			icono = boton.find(".fa");
			icono.toggleClass("fa-trash fa-spinner fa-spin fa-floppy-o");
			var id_clientes = boton.data('id_value');
			var fila = boton.closest('tr');
			var elimina = function() {
				$.ajax({
					url: 'control/fila_delete.php',
					method: 'POST',
					dataType: 'JSON',
					data: {
						tabla: "clientes",
						id_campo: "id_clientes",
						id_valor: id_clientes
					}
					}).done(function(respuesta) {
					boton.prop('disabled', false);
					console.log(respuesta.mensaje);
					if (respuesta.estatus == "success") {
						fila.fadeOut(1000);
						icono.toggleClass("fa-trash fa-spinner fa-spin fa-floppy-o");
						console.log('se eliminino');
						} else {
						console.log(respuesta.error);
					}
				});
			};
			alertify.confirm('Confirmacion', '¿Desea eliminarlo?', elimina, function() {
				icono.toggleClass("fa-trash fa-spinner fa-spin fa-floppy-o");
				boton.prop('disabled', false);
			});
			
		});
		
		$('.btn_editar').click(function() {
			
			var boton = $(this);
			var id_value = boton.data('id_value');
			var icono = boton.find('.fa');
			var $formulario = $('#form_edit');
			
			boton.prop("disabled", true);
			icono.toggleClass("fa-spinner fa-spin fa-edit");
			$formulario.find(".action").val("update");
			
			
			$.ajax({
				url: 'control/buscar_normal.php',
				method: 'POST',
				dataType: 'JSON',
				data: {
					campo: 'id_clientes',
					tabla: 'clientes',
					id_campo: id_value
				}
				}).done(function(respuesta) {
				if (respuesta.encontrado == 1) {
					$.each(respuesta["fila"], function(name, value) {
						$("#" + name).val(value);
					});
				}
				$('#modal_edit').modal('show');
				}).fail(function(xhr, error, errnum){
				alertify.error(error);
				
				}).always(function() {
				boton.prop("disabled", false);
				icono.toggleClass("fa-spinner fa-spin fa-edit");
				
			});
			
		});
		
		
		
		
	});
}
