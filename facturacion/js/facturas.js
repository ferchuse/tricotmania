var filtros = $("#form_filtros").serialize();

function buscarCliente(event) {
	var value = $(this).val().toLowerCase();
	$("#lista_facturas tr").filter(function() {
		$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
	});
}	

$(document).ready(function() {
	
	cargarTabla(filtros);
	
	
	$("#abono").keyup( function calculaSaldo(){
		var saldo_anterior = Number($("#saldo_anterior").val());
		var abono = Number($(this).val());
		var saldo_restante = saldo_anterior - abono;
		
		$("#saldo_restante").val(saldo_restante);
		
	});
	
	$(".filtro").change(function(){
		filtros = $("#form_filtros").serialize();
		cargarTabla(filtros);
		
	});
	
	
	
	//--------FILTRO--------
	
	
	
	$(".exportar").click(function(){
		
		$('#tabla_reporte').tableExport(
			{
				type:'excel',
				tableName:'Reporte', 
				ignoreColumn: [9],
				escape:'false'
			});
	});
	
	
	//-------------FILTROS------------------------------
	
	$('#id_select').change(function seleccionNiveles() {
		var niveles = $('#id_select option:selected').data('id_nivel');
		console.log(niveles);
		filtros['niveles'] = $('#id_select option:selected').data('id_nivel');
		filtros['id_grados'] = $('#id_select option:selected').val();
		
		cargarTabla(filtros);
		console.log(filtros);
	});



$("#form_pago").submit( guardarPago );


$("#form_correo").submit(  function enviarCorreo(event){
	
	var boton = $(this).find(":submit");
	var icono = boton.find(".fa");
	
	event.preventDefault();
	icono.toggleClass("fa-envelope fa-spinner fa-spin");
	boton.prop('disabled', true);
	
	$.ajax({
		url: 'facturacion/enviar_factura.php',
		dataType: 'JSON',
		method: 'GET',
		data:$("#form_correo").serialize()
		}).done(function(respuesta){
		console.log("Respuesta Correo", respuesta);
		if(respuesta.estatus_correo == "success"){
			
			alertify.success("Se ha enviado correctamente"); 
			$("#modal_correo").modal("hide");
		}
		else{
			alertify.error("Ocurrio un error" +  respuesta.mensaje_correo); 
			
		}			
		}).always(function(){
		icono.toggleClass("fa-envelope fa-spinner fa-spin");
		boton.prop('disabled', false);
		
	});
	
});
});

function cargarTabla(filtros) {
	var cargador = "<tr><td class='text-center' colspan='10'><i class='fa fa-spinner fa-spin fa-3x'></i></td></tr>";
	$('#lista_facturas').html(cargador);
	$.ajax({
		url: 'control/lista_facturas.php',
		method: 'GET',
		data: filtros
		}).done(function(respuesta) {
		$('#lista_facturas').html(respuesta);
		
		$('.btn_cancelar').click(confirmarCancelacion);
		
		$('.btn_eliminar').click(confirmaEliminar);
		$('.btn_pago').click(mostrarModalPago);
		$("#buscar_cliente").keyup(buscarCliente);
		
		$('.btn_correo').click(function modal_correo() {
			console.log("modal_correo()");
			$("#correo").val($(this).data("correo"));
			$("#url_xml").val($(this).data("url_xml"));
			$("#url_pdf").val($(this).data("url_pdf"));
			
			$("#modal_correo").modal("show");
			
		});
		
	});
}


function confirmaEliminar() {
	var boton = $(this);
	boton.prop('disabled', true);
	icono = boton.find(".fa");
	icono.toggleClass("fa-trash fa-spinner fa-spin ");
	var folio_facturas = boton.data('folio_facturas');
	var id_facturas = boton.data('id_facturas');
	var fila = boton.closest('tr');
	
	alertify.confirm('Confirmacion', '¿Deseas Eliminar esta factura?', eliminarFactura, function(){
		icono.toggleClass("fa-trash fa-spinner fa-spin");
		boton.prop('disabled', false);
	});
	
	function eliminarFactura(evet,value) {
		$.ajax({
			url: 'control/eliminar_factura.php',
			method: 'GET',
			data:{
				folio_facturas: folio_facturas,
				id_facturas: id_facturas
			}
			}).done(function(respuesta){
			if(respuesta.estatus == "success"){
				fila.fadeOut(200)
				alertify.success("Factura Eliminada Correctamente"); 
				
			}
			else{
				alertify.error(respuesta.mensaje); 
				
			}
			
			cargarTabla(filtros);
			}).fail(function(xhr, error,errnum ){
			alertify.error("Ocurrio un error" + error);
			}).always(function(){
			icono.toggleClass("fa-trash fa-spinner fa-spin ");
			boton.prop('disabled', false);
		});
	}
}

function confirmarCancelacion() {
	var boton = $(this);
	boton.prop('disabled', true);
	icono = boton.find(".fa");
	icono.toggleClass("fa-times fa-spinner fa-spin ");
	var folio_facturas = boton.data('folio_facturas');
	var id_facturas = boton.data('id_facturas');
	var uuid = boton.data('uuid');
	var fila = boton.closest('tr');
	function cancelarFactura(evet,value) {
		$.ajax({
			url: 'facturacion/cancelar_factura.php',
			method: 'POST',
			data:{
				
				"uuid": uuid,
				motivo_cancelacion: value,
				folio_facturas: folio_facturas,
				id_facturas: id_facturas
			}
			}).done(function(respuesta){
			if(respuesta.respuesta_pac.codigo_mf_numero == 0){
				
				alertify.success("CFDI Cancelado correctamente"); 
				
			}
			else{
				
				alertify.error(respuesta.respuesta_pac.codigo_mf_texto)
			}
			
			alertify.success("CFDI Cancelado correctamente"); 
			cargarTabla(filtros);
			}).fail(function(xhr, error,errnum ){
			alertify.error("Ocurrio un error" + error);
			}).always(function(){
			icono.toggleClass("fa-times fa-spinner fa-spin ");
			boton.prop('disabled', false);
		});
	}
	
	alertify.prompt('Confirmacion', '¿Deseas Cancelar esta factura?','Escribe el motivo', cancelarFactura, function() {
		icono.toggleClass("fa-times fa-spinner fa-spin");
		boton.prop('disabled', false);
	});
	
}

function mostrarModalPago(){
	var serie_actual = $("#serie_actual").val();
	var serie = serie_actual[0];
	var folio = serie_actual.substring(1, serie_actual.length );
	
	
	$("#modal_pago").modal("show");
	$("#id_facturas").val($(this).data("id_facturas"));
	$("#saldo_anterior").val($(this).data("saldo_actual"));
	$("#abono").val($(this).data("saldo_actual"));
	$("#saldo_restante").val("0");
	$("#serie").val(serie); 
	$("#folio").val(folio);
	
	
	$("#mensaje_error").addClass('hidden');	
	$("#mensaje_timbrado").addClass('alert-success hidden');	
	$("#mensaje_pdf").addClass('alert-success hidden');	
	
}


function guardarPago(event){
	
	event.preventDefault();
	
	var boton = $(this).find(":submit");
	var icono = boton.find(".fa");
	
	icono.toggleClass("fa-save fa-spinner fa-spin");
	boton.prop('disabled', true);
	
	
	$("#mensaje_error").html("") ;
	$("#mensaje_timbrado").removeClass('alert-danger hidden');	
	$("#mensaje_timbrado").find(".fa").removeClass('fa-times');	
	$("#mensaje_timbrado").find(".fa").addClass('fa-spinner fa-spin');	
	
	
	$.ajax({
		url: 'facturacion/pago.php',
		dataType: 'JSON',
		method: 'POST',
		data:$("#form_pago").serialize()
		}).done( function afterGuardarPago(respuesta){
		if(respuesta.timbrado.codigo_mf_numero == 0){
			$("#mensaje_pdf").removeClass('hidden');	
			$("#mensaje_timbrado").find(".fa").removeClass('fa-spinner fa-spin');	
			$("#mensaje_timbrado").find(".fa").addClass('fa-check');	
			console.log("id_factura_nueva", respuesta.id_factura_nueva)
			$.ajax({
				url: 'facturacion/generar_pdf.php',
				method: 'GET',
				data: 
				{
					id_facturas :respuesta["id_factura_nueva"]
				}
				
				}).done(function afterGeneraPDF(respuesta){
				console.log(respuesta);
				if(respuesta.estatus_pdf){
					alertify.success("Se ha guardado correctamente"); 
					$("#modal_pago").modal("hide");
					window.location.reaload();
					
				}
				else{
					alertify.error("Ocurrio un error" +  respuesta.result_factura); 
					
				}	
				// $("#mensaje_pdf").find(".fa").toggleClass('fa-spinner fa-spin fa-check');	
				
				
				}).always(function(){
				
				icono.toggleClass("fa-save fa-spinner fa-spin");
				boton.prop('disabled', false);
				
			});
			}else{
			$("#mensaje_timbrado").toggleClass('alert-success alert-danger');	
			$("#mensaje_timbrado").find(".fa").removeClass('fa-spinner fa-spin');	
			$("#mensaje_timbrado").find(".fa").addClass('fa-times');	
			
		}
		}).always(function(){
		
	});
	
}
