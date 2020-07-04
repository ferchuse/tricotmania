
listarRegistros();


$("#nuevo").click(function(){
	$("#form_edicion")[0].reset();
	$("#modal_edicion").modal("show")
	
});

$("#form_edicion").submit(guardarRegistro);
$("#lista_registros").on("click", ".btn_editar", cargarRegistro );
$("#lista_registros").on("click", ".btn_borrar", confirmaBorrar );


function listarRegistros() {
	console.log("listarRegistros()");
	// boton = $(this).find(":submit");
	// icono = boton.find("i");
	
	// boton.prop("disabled", true);
	// icono.toggleClass("fa-search fa-spinner fa-spin");
	
	
	$.ajax({
		"url": "lista_calidades.php",
		}).done(function(respuesta){
		
		$("#lista_registros").html(respuesta);
		
	});
}


function cargarRegistro(event){
	console.log("event", event);
	let $boton = $(this);
	let $icono = $(this).find(".fas");
	let $id_registro = $(this).data("id_registro");				
	$boton.prop("disabled", true);
	$icono.toggleClass("fa-edit fa-spinner fa-spin");	
	
	$.ajax({ 
		"url": "../funciones/fila_select.php",
		"dataType": "JSON",
		"data": {
			"tabla": "calidades",
			"id_campo": "id_calidades",
			"id_valor": $id_registro						
		}
		}).done( function alTerminar (respuesta){					
		console.log("respuesta", respuesta);
		$boton.prop("disabled", false);
		$icono.toggleClass("fa-edit fa-spinner fa-spin"); 
		$("#modal_edicion").modal("show")
		$("#id_calidades").val(respuesta.data.id_calidades);                        
		$("#calidad").val(respuesta.data.calidad);                        
		
	})
}


function guardarRegistro(event){
	console.log("guardarRegistro()")
	event.preventDefault()
	let $boton = $(this).find(':submit');
	let $icono = $(this).find(".fas");
	
	$boton.prop("disabled", true);
	$icono.toggleClass("fa-save fa-spinner fa-spin");				
	
	$.ajax({ 
        "url": "guardar_calidades.php",
        "dataType": "JSON",
        "method": "POST",
        "data": $("#form_edicion").serialize()
        }).done( function alTerminar (respuesta){
		
		if(respuesta.estatus == "success"){
			
			alertify.success(respuesta.mensaje)
			$("#modal_edicion").modal("hide");
			listarRegistros();
		}
		else{
			
			alertify.error(respuesta.mensaje);
		}
		}).always( function (){
		
		$boton.prop("disabled", false);
		$icono.toggleClass("fa-save fa-spinner fa-spin");				
		
	}).fail();
	
}	


function confirmaBorrar(event) {
	console.log("confirmaBorrar()")
	var boton = $(this);
	var id_registro = boton.data('id_registro');
	var fila = boton.closest('tr');
	
	
	
	
	alertify.confirm()
	.setting({
		'reverseButtons': true,
		'labels' :{ok:"SI", cancel:'NO'},
		'title': "Confirmar" ,
		'message': "¿Deseas borrar?" ,
		'onok':borrarRegistro
		
	}).show();
	
	
	function borrarRegistro(evnt,value) {
		
		boton.prop('disabled', true);
		icono = boton.find(".fa");
		
		$.ajax({
			url: '../funciones/fila_delete.php',
			method: 'POST',
			data:{ 
				"tabla": 'calidades',
				"id_campo": "id_calidades",
				"id_valor": id_registro
				
			}
			}).done(function(respuesta){
			
			fila.fadeOut(500);
			}).fail(function(){
			alertify.error("Ocurrió un error");
			
			}).always(function(){
			icono.toggleClass("fa-times fa-spinner fa-spin");
			boton.prop('disabled', false);
			
		});
	}
}