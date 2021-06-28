$(document).ready(function(){
	
	$('#form_arqueo').on('submit', guardarArqueo);
	
	
	$('#btn_arqueo').click(nuevoArqueo);
	
	$('.cantidad').on('keyup', sumarArqueo);
	$('.cantidad').on('focus', function selectOnFocus(event) {$(this).select()});
	
});

function nuevoArqueo(event){
	$("#resumen").hide();
	$("#form_arqueo")[0].reset();
	$("#modal_arqueo").modal("show");
	
}

function guardarArqueo(event){
	console.log("guardarArqueo()")
	event.preventDefault();
	
	let form = $(this);
	let boton = form.find(':submit');
	let icono = boton.find('.fa');
	let datos = form.serializeArray();
	
	
	boton.prop('disabled',true);
	icono.toggleClass('fa-save fa-spinner fa-pulse ');
	
	$.ajax({
		url: 'consultas/guardar_arqueo.php',
		method: 'POST',
		dataType: 'JSON',
		
		data: datos
		
		}).done(function(respuesta){
		if(respuesta.estatus == 'success'){
			
			$("#modal_arqueo").modal("hide");
			imprimirArqueo(respuesta.nuevo_id);
		}
		else{
			alertify.error('Ocurrio un error');
		}
		}).always(function(){ 
		boton.prop('disabled',false);
		icono.toggleClass('fa-save fa-spinner fa-pulse');
	});
	
}




function sumarArqueo(){
	console.log("sumarArqueo()");
	
	let subtotal = 0;
	let importe_total = 0;
	let fondo_caja = Number($("#arqueo_fondo_caja").val());
	let $fila = $(this).closest("tr");
	let denominacion = Number($fila.find(".cantidad").data('denomi'));
	let cantidad = Number($fila.find(".cantidad").val());
	let importe = cantidad * denominacion;
	
	$fila.find('.importe').val(importe);
	
	
	$(".importe").each( function sumarImportes(index, item){
		subtotal += Number($(item).val());
	});
	
	console.log("subtotal:" , subtotal)
	
	let total = subtotal - fondo_caja;
	
	
	$("#arqueo_subtotal").val(subtotal.toFixed(2));
	
	$("#arqueo_total").val(total.toFixed(2));
}


function imprimirArqueo(nuevo_id){
	console.log("imprimirArqueo()");
	
	$("#resumen").removeClass("visible-print");
	$("#resumen").addClass("hidden-print");
	
	
	$.ajax({
		url: "imprimir_arqueo.php",
		data:{
			id_registro : nuevo_id
		}
		}).done(function (respuesta){
		
		printService.submit({
			'type': 'LABEL',
			'raw_content': respuesta
		});
		}).always(function(){
		
		
	});
}		