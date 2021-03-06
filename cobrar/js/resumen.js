
var printService = new WebSocketPrinter();

$(document).ready( onLoad);

function onLoad(event){
	
	
	
	$('#form_cobrar').submit(guardarVenta);
	
	$('#buscar_venta').keyup(buscarVenta);
	
	$('#pago').keyup(calculaCambio);
	$('#efectivo').keyup(calculaCambio);
	
	$("#forma_pago").change(eligeFormaPago);
	
	$("#folios_multiples").keyup(agregarTickets);
	$("#btn_cobrar_varios").click(cobrarVarios);
	
}



function cobrarVarios(){
	console.log("cobrarVarios()")
	// str = str.substring(0,len-1);
	
	//mostrar modal
	var folios = $("#folios_multiples").val();
	
	//Quita Coma al final
	// while(folios.substring(folios.length -1,1)  == ","){
		
		// console.log("folios", folios)
	// }
		folios = folios.substring(0,folios.length - 1);
	
	//  if (str.substr(len-1,1) == ",") {
	
	$.ajax({
		url: 'consultas/buscar_venta.php',
		method: 'GET',
		dataType: 'JSON',
		data:  {
			"id_ventas": folios
			
		}
		}).done( function(respuesta){
		if(respuesta.num_rows == 0){
			
			alertify.error("Venta no encontrada");
			return false;
		}
		
		if(respuesta.venta.estatus_ventas == 'PENDIENTE'){
			//mostrar Modal de Cobro
			
			$("#subtotal").val(respuesta.venta.total_ventas)
			$("#efectivo").val(respuesta.venta.total_ventas)
			$("#pago").val(respuesta.venta.total_ventas)
			$("#pago_id_ventas").val(folios)
			cobrar();
		
	}
	else{
		alertify.error('Esta folio se encuentra ' + respuesta.venta.estatus_ventas );
		
	}
	}).always(function(){
	
	});
	
	
}

function agregarTickets(event){
	console.log("agregarTickets")
	if(event.key == "Enter"){
		$("#folios_multiples").val($("#folios_multiples").val() + ",")	
	}
}
function eligeFormaPago(event){
	console.log("eligeFormaPago")
	console.log($(this).val())
	// $("#forma_pago") hacer requeridos todos los input visibles y no requeridso los invisibles
	
	switch($(this).val()){
		
		case "efectivo":
		$("#div_efectivo").removeClass("hidden")
		$("#div_tarjeta").addClass("hidden")
		
		
		
		$("#efectivo").prop("readonly", true)
		$("#efectivo").val($("#subtotal").val())
		
		$("#tarjeta").val(0)
		$("#comision").val(0)
		break;
		
		case "tarjeta":
		
		$("#div_efectivo").addClass("hidden")
		$("#div_tarjeta").removeClass("hidden")
		$("#tarjeta").val($("#subtotal").val())
		$("#efectivo").val(0);
		// $("#tarjeta").prop("readonly", false);
		
		break;
		
		case "mixto":
		$("#efectivo").prop("readonly", false)
		$("#efectivo").val($("#subtotal").val())
		$("#efectivo").focus()
		
		$("#div_efectivo").removeClass("hidden")
		$("#div_tarjeta").removeClass("hidden")
		$("#tarjeta").val("0");
		$("#tarjeta").prop("readonly", false);
		// calculaComision()
		break;
		
		
		
	}
}

function buscarVenta(event){
	
	if(event.key == "Enter"){
		var id_ventas = $(this).val();
		$.ajax({
			url: 'consultas/buscar_venta.php',
			method: 'GET',
			dataType: 'JSON',
			data:  {
				"id_ventas": id_ventas
				
			}
			}).done( function(respuesta){
			if(respuesta.num_rows == 0){
				
				alertify.error("Venta no encontrada");
				return false;
			}
			
			if(respuesta.venta.estatus_ventas == 'PENDIENTE'){
				//mostrar Modal de Cobro
				
				$("#subtotal").val(respuesta.venta.total_ventas)
				$("#efectivo").val(respuesta.venta.total_ventas)
				$("#pago").val(respuesta.venta.total_ventas)
				$("#pago_id_ventas").val(id_ventas)
				cobrar();
				
			}
			else{
				alertify.error('Esta folio se encuentra ' + respuesta.venta.estatus_ventas );
				
			}
			}).always(function(){
			
		});
		
	}
}


function guardarVenta(event){
	event.preventDefault();
	console.log("guardarVenta", event.type);
	
	var boton = $(this).find(":submit");
	var icono = boton.find('.fa');
	
	
	boton.prop('disabled',true);
	icono.toggleClass('fa-save fa-spinner fa-spin');
	
	
	
	return $.ajax({
		url: '../ventas/guardar_pago.php',
		method: 'POST',
		dataType: 'JSON',
		data:{
			
			"id_ventas": $("#pago_id_ventas").val(),
			"forma_pago": $("#forma_pago").val(),
			"efectivo": $("#efectivo").val(),
			"tarjeta": $("#tarjeta").val(),
			"pago": $("#pago").val(),
			"cambio": $("#cambio").val(),
			"estatus_ventas": "PAGADO",
			
		}
		}).done(function(respuesta){
		if(respuesta.estatus_venta == "success"){
			alertify.success('Venta Guardada');
			//Resetea la venta
			
			
			$("#modal_cobrar").modal("hide");
			$("#buscar_venta").val("");
			$("#buscar_venta").focus();
			
			// limpiarVenta();
			
			// console.log("Venta Activa", $("#tabs_ventas>li.active input").val("Mostrador"));
			imprimirTicket( respuesta.id_ventas)
			
			setTimeout(function(){
				imprimirTicket(respuesta.id_ventas)
			}, 4000);
			
			$("#form_cobrar")[0].reset();
			
		}
		}).fail(function(xhr, error, errnum){
		alertify.error('Ocurrio un error' + error);
		}).always(function(){
		boton.prop('disabled',false);
		icono.toggleClass('fa-save fa-spinner fa-spin');
	});
	
}

function cobrarEImprimir(evt){
	console.log("cobrarEImprimir()")
	evt.data = {"imprimir": true};
	evt.type = "submit";
	
	if($(".tabla_venta:visible tbody tr").length == 0){
		
		alertify.error('No hay productos');
		return false;
	}
	
	
	
	$("#imprimir").prop('disabled',true);
	$("#imprimir").find(".fas").toggleClass('fa-print fa-spinner fa-spin');
	
	guardarVenta(evt).done(function(respuesta){
		
		$("#imprimir").prop('disabled',false);
		$("#imprimir").find(".fas").toggleClass('fa-print fa-spinner fa-spin');
		imprimirTicket(respuesta.id_ventas);
		
		setTimeout(function(){
			imprimirTicket(respuesta.id_ventas)
		}, 6000);
	})
	
}

function cobrar(){
	console.log("cobrar()")
	$("#modal_cobrar").modal("show") ;
	
	// $("#efectivo").val($(".total:visible").val());
	// $("#tarjeta").val($(".total:visible").val());
	// $("#pago").val($("#efectivo").val());
	$("#pago").select();
	calculaCambio();
}



function calculaCambio(){
	console.log("calculaCambio()")
	let efectivo = $("#efectivo").val();
	let pago = $("#pago").val();
	let cambio = pago - efectivo;
	$("#cambio").val(cambio.toFixed(2));
}									



function contarSeleccionados(){
	console.log( ("contarSeleccionados()"));
	$("#cant_seleccionados").text($(".seleccionar:checked").length);
	
	
	var folios = $(".seleccionar:checked").map(function(){
		return $(this).val();
	}).get().join(",");
	
	
	$("#folios_seleccionados").val(folios);
	console.log( ("folios") , folios);	
	
	if($(".seleccionar:checked").length > 0 ){
		$("#facturar").prop("disabled", false);
	}
	else{
		$("#facturar").prop("disabled", true);
	}
}

function checkAll(){
	console.log("checkAll");
	if($(this).prop("checked")){
		$(".seleccionar").prop("checked", true);
	}
	else{
		
		$(".seleccionar").prop("checked", false);
		
	}
	contarSeleccionados();
	
}

function imprimirCorte(event){
	// $("#ticket").hide();
	// $("#resumen").removeClass("hidden-print");
	// $("#resumen").addClass("visible-print");
	// window.print();
	
	
	
	var password = prompt("Ingresa Contraseña", "");
	if (password == "tricot") {
		printService.submit({
			'type': 'LABEL',
			'raw_content': $("#corte_b64").val()
		});
	}
	else{
		alert("Contraseña Incorrecta")
	}
	
	
}


function nuevoIngreso(){
	alertify.prompt("Nuevo Ingreso", "Cantidad" , 0, guardarIngreso, function(){});
	
}

function guardarIngreso(event, value){
	var fecha_ingresos = new Date().toString('yyyy-MM-dd');
	var hora_ingresos = new Date().toString('HH:mm:ss');
	
	$.ajax({
		url: '../funciones/fila_insert.php',
		method: 'POST',
		dataType: 'JSON',
		data:  {
			"tabla": "ingresos",
			"valores": [
				{"name": "cantidad_ingresos", "value": value },
				{"name": "fecha_ingresos", "value":  fecha_ingresos},
				{"name": "hora_ingresos", "value":  hora_ingresos},
				{"name": "id_turnos", "value":  $("#id_turnos").val()}
			]
		}
		}).done( function(respuesta){
		if(respuesta.estatus == 'success'){
			alertify.success('Guardado correctamente');
			window.location.reload(true);
		}
		else{
			alertify.error('Ha ocuurido un error');
			console.log(respuesta.mensaje);
		}
		}).always(function(){
		
	});
}


function cambiaFormaPago(event){
	console.log("cambiaFormaPago()")
	
	
	var forma_pago = $(this).val();
	var total = $("#total").text();
	
	if(forma_pago == "efectivo"){
		
		var efectivo = total;
		var tarjeta = 0;
	}
	else{
		
		var tarjeta = total;
		var efectivo = 0;
	}
	
	
	$.ajax({
		url: '../funciones/fila_update.php',
		method: 'POST',
		dataType: 'JSON',
		data:  {
			"tabla": "ventas",
			"id_campo": "id_ventas",
			"id_valor": $("#id_ventas").text(),
			"valores": [
				{"name": "forma_pago", "value": forma_pago },
				{"name": "efectivo", "value": efectivo },
				{"name": "tarjeta", "value": tarjeta }
			]
		}
		}).done( function(respuesta){
		if(respuesta.estatus == 'success'){
			alertify.success('Guardado correctamente');
			window.location.reload(true);
		}
		else{
			alertify.error('Ha ocuurido un error');
			console.log(respuesta.mensaje);
		}
		}).always(function(){
		
	});
}



function cambiarFecha(){
	
	$("#id_turnos").prop("disabled", true);
	$("#form_resumen").submit();
	
}

function filtrarUsuario(){
	
	
	$("#form_resumen").submit();
	
}


function imprimirTicket(id_ventas){
	$("#arqueo").hide();
	$("#resumen").hide();
	$("#arqueo").addClass("hidden-print");
	$("#resumen").addClass("hidden-print");
	
	console.log("btn_ticketPago");
	// var id_ventas = $(this).data("id_ventas");
	var boton = $(this);
	var icono = boton.find(".fa");
	
	boton.prop("disabled",true)
	icono.toggleClass("fa-print fa-spinner fa-spin");
	
	$.ajax({
		url: "../ventas/imprimir_ticketpos.php" ,
		data:{
			"id_ventas" : id_ventas
		}
		}).done(function (respuesta){
		
		
		printService.submit({
			'type': 'LABEL',
			'raw_content': respuesta
		});
		}).always(function(){
		
		boton.prop("disabled", false);
		icono.toggleClass("fa-print fa-spinner fa-spin");
		
	});
	
}



function confirmaCancelarVenta(event) {
	console.log("confirmaCancelarVenta()")
	var boton = $(this);
	var id_registro = boton.data('id_ventas');
	var fila = boton.closest('tr');
	
	boton.prop('disabled', true);
	icono = boton.find(".fa");
	
	
	alertify.confirm()
	.setting({
		'reverseButtons': true,
		'labels' :{ok:"SI", cancel:'NO'},
		'title': "Confirmar" ,
		'message': "¿Deseas cancelar esta venta?" ,
		'onok':cancelarVenta
	}).show();
	
	
	function cancelarVenta(evnt,value) {
		$.ajax({
			url: 'consultas/cancelar_ventas.php',
			method: 'POST',
			data:{ 
				"estatus_ventas": 'CANCELADO',
				"id_ventas": id_registro,
				"motivo": value
				
			}
			}).done(function(respuesta){
			alertify.success("Se ha cancelado el pago"); 
			window.location.reload();
			
			}).fail(function(){
			alertify.error("Ocurrió un error");
			
			}).always(function(){
			icono.toggleClass("fa-times fa-spinner fa-spin");
			boton.prop('disabled', false);
			
		});
	}
}

function confirmaCancelarIngreso(event) {
	console.log("confirmaCancelarIngreso()")
	var boton = $(this);
	var id_registro = boton.data('id_registro');
	var fila = boton.closest('tr');
	
	boton.prop('disabled', true);
	icono = boton.find(".fa");
	
	
	alertify.confirm()
	.setting({
		'reverseButtons': true,
		'labels' :{ok:"SI", cancel:'NO'},
		'title': "Confirmar" ,
		'message': "¿Deseas cancelar esta Entrada?" ,
		'onok':cancelarIngreso,
		'oncancel': function(){
			boton.prop('disabled', false);
			
		}
	}).show();
	
	
	function cancelarIngreso(evnt,value) {
		$.ajax({
			url: 'consultas/cancelar_ingresos.php',
			method: 'POST',
			data:{ 
				"id_registro": id_registro,
				"motivo": value
				
			}
			}).done(function(respuesta){
			alertify.success("Cancelado"); 
			window.location.reload();
			
			}).fail(function(){
			alertify.error("Ocurrió un error");
			
		}).always(function(){
			icono.toggleClass("fa-times fa-spinner fa-spin");
			boton.prop('disabled', false);
			
		});
	}
}



function verTicket(){
	
	console.log("verTicket");
	var id_ventas = $(this).data("id_ventas");
	var boton = $(this).prop("disabled",true);
	var icono = boton.find(".fa");
	icono.toggleClass("fa-eye fa-spinner fa-spin");
	
	$.ajax({
		url: "forms/modal_imprimir_venta.php",
		dataType: "HTML",
		data:{ id_ventas:id_ventas}
		}).done(function(respuesta){
		$('#ver_venta').html(respuesta);
		$('#modal_ticket').modal("show");
		
		$("#modal_ticket").on("change", '.forma_pago', cambiaFormaPago);
		
		boton.prop("disabled",false);
		icono.toggleClass("fa-print fa-spinner fa-spin");
		
	});
	// console.log("pago");
}

function confirmaCerrarTurno(){
	
	
	alertify.confirm()
	.setting({
		'reverseButtons': true,
		'labels' :{ok:"SI", cancel:'NO'},
		'title': "Confirmar" ,
		'message': "¿Desea cerrar el turno?" ,
		'onok':cerrarTurno
	}).show();
}


function cerrarTurno(){
	
	$.ajax({
		'method': 'POST',
		'dataType': 'JSON',
		'url': 'cerrar_turno.php',
		'data': {
			id_turnos:$("#id_turnos").val(),
			saldo_final:$("#saldo_final").val(),
			id_usuarios:$("#id_usuarios").val()
		}
		}).done(function(respuesta){
		if(respuesta.cierra_turno.estatus == "success"){
			
			location.href = '../login/logout.php';
		}
		else{
			
		}
		
	}).always();
	
	
	}				