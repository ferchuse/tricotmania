var printService = new WebSocketPrinter();
var producto_elegido ;
var tipo_ticket = "cliente";

function round(value, step) {
	step || (step = 1.0);
	var inv = 1.0 / step;
	return Math.round(value * inv) / inv;
}

function DecimalRound(DValue, DPrecision){
	return Math.round(DValue / DPrecision) * DPrecision;
}



console.log(DecimalRound(10.26, 2))
console.log(DecimalRound(10.26, .5))

function cargarPendientes(event){
	console.log("cargarPendientes");
	console.log("tabs", $("#tabs_ventas .cremeria").length);
	console.log("content", $(".tab-content .cremeria").length);
	
	$.ajax({
		url: "../funciones/ventas_pendientes.php",
		dataType: "JSON"
	})
	.done(renderPendientes);
	
}

function cerrarTab(event){
	
	id_tab = $(this).closest("li").remove().find("a").attr("href");
	console.log("borrar tab", id_tab);
	$(id_tab).remove();
	//borrar tab();
}
function renderPendientes(respuesta){
	if(respuesta.num_filas == 0){
		alertify.warning("No hay Ventas Pendientes");
		return ;
		
	}
	
	var tab_index = $("#tabs_ventas li").length + 1;
	console.log("tab_index", tab_index);
	//Borra contenido anterior
	$("#tabs_ventas .cremeria").remove();
	$(".tab-content .cremeria").remove();
	
	$.each(respuesta.ventas, function agregaTabs(i, venta){
		console.log("tab_index", tab_index);
		$("#tabs_ventas").append(
			`<li class="cremeria">
			<a data-toggle="tab" href="#tab${tab_index}">
			
			<input class='nombre_cliente' value='${venta["nombre_cliente"]}'>
			<input type="hidden" class="id_ventas" value="${venta["id_ventas"]}">
			</a>
		</li>`);
		
		renderProductos(tab_index, venta);
		
		tab_index++;
		
		$("#tabs_ventas .close").click(cerrarTab);
	});
	
	//anexa Tab
	
	
	//Anexa contenido del tab
	console.log("agregando productos");
}

function renderProductos(tab_index, venta){
	console.log("venta", venta)
	console.log("productos", venta.productos)
	var productos = "";
	
	
	$.each(venta.productos, function(i, producto){
		productos+= `<tr class="bg-info">
		<td class="col-sm-1">
		<input hidden class="id_productos"  value="${producto['id_productos']}">
		<input hidden class="unidad" value='${producto['unidad_productos']}'>
		<input hidden class="descripcion" value='${producto['descripcion_productos']}'>
		<input hidden class="precio_mayoreo" value='${producto['precio_mayoreo']}'>
		<input hidden class="precio_menudeo" value='${producto['precio_menudeo']}'>
		<input hidden class="ganancia_porc" value='${producto['ganancia_menudeo_porc']}'>
		<input hidden class="costo_proveedor" value='${producto['costo_proveedor']}'>
		<input type="number"  step="any" class="cantidad form-control text-right"  value='${producto['cantidad']}'>
		</td>
		<td class="text-center">${producto['unidad_productos']}</td>
		<td class="text-center">${producto['descripcion_productos']}</td>
		<td class="col-sm-1"><input readonly type="number" class='precio form-control' value='${producto.precio}'> </td>
		<td class="col-sm-1">
		<input readonly type="number" class='importe form-control text-right' value=${producto.importe}>
		</td>
		<td class="col-sm-1">	
		<input class="existencia_anterior form-control" readonly  value='${producto['existencia_productos']}'> 
		</td>
		<td class="text-center">
		<button title="Eliminar Producto" class="btn btn-danger btn_eliminar">
		<i class="fa fa-trash"></i>
		</button> 
		<label class="custom_checkbox">
		Mayoreo
		<input class="mayoreo" type="checkbox">
		<span class="checkmark"></span>
		</label>
		</td>
		</tr>`;
		
		
	});
	
	console.log("productos_html", productos);
	
	$(".tab-content").append(
		`<div id="tab${tab_index}" class="tab-pane cremeria">
		<div class="productos">
		<table class="tabla_venta table table-bordered table-condensed">
		<thead class="bg-success">
		<tr>
		<th class="text-center">Cantidad</th>
		<th class="text-center">Unidad</th>
		<th class="text-center">Descripcion del Producto</th>
		<th class="text-center">Precio Unitario</th>
		<th class="text-center">Importe</th>
		<th class="text-center">Existencia</th>
		<th class="text-center">Acciones</th>
		</tr>
		</thead>
		<tbody >
		${productos}
		</tbody>
		</table>
		</div>
		<section id="footer">
		<div class="row">
		<div class="col-sm-1 lead">
		<label>Artículos	</label>
		<input class="form-control articulos" type="number" autocomplete="off" readonly value="${venta.articulos}">
		</div>
		<div class="col-sm-8 text-right">
		</div>
		<div class="col-sm-1 h2">
		<strong>TOTAL:</strong>
		</div>
		<div class="col-sm-2 h1">
		<input readonly type="text" class="form-control input-lg text-right total" value="${venta.total_ventas}" name="total">
		</div>
		</div>
		</section>
		</div>
	`);
	
	
	//Asigna Callbacks de eventos
	$(".mayoreo").change(aplicarMayoreoProducto);
	$(".cantidad").keyup(sumarImportes);
	$(".cantidad").change(sumarImportes);
	$(".btn_eliminar").click(eliminarProducto);
	$('#tabs_ventas a').on('shown.bs.tab', function(event){
		var active = $(event.target).text();         // active tab
		var previous = $(event.relatedTarget).text();  // previous tab
		console.log("active",active)
		console.log("previous",previous)
		sumarImportes();
	});
	
	
	$("input").focus( function selecciona_input(){
		$(this).select();
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
$(document).on('keydown', disableFunctionKeys);

$(document).ready( function onLoad(){
	
	// $('#imprimir').click(cobrarEImprimir);
	
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		e.target // newly activated tab
		e.relatedTarget // previous active tab
	})
	
	$('.total_descuento').keyup(calcularDescuento);
	$('.bg-info').keydown(navegarFilas);
	$('#btn_refresh').click(cargarPendientes);
	
	$("#btn_refresh").click();
	
	
	$('#form_pago').submit(cobrarEImprimir);
	$('#form_granel').submit(agregarGranel);
	$('#btn_pendiente').click( pedirNombre);
	
	function pedirNombre(event){
		let nombre_cliente =  $("#tabs_ventas li.active input").val();
		
		
		if($(".tabla_venta:visible tbody tr").length == 0){
			
			alertify.error('No hay productos');
			return false;
		}
		
		alertify.prompt( 'Venta Pendiente', 'Nombre del Cliente', nombre_cliente
			, function onAccept(evt, value) { 
				guardarVenta(value);
				
			}
			, function onCancel() { 
			alertify.error('Cancel') });
			
	}
	
	$('#form_agregar_producto').submit(function(event){
		event.preventDefault();
	})
	
	alertify.set('notifier','position', 'top-right');
	
	
	//Autocomplete Productos https://github.com/devbridge/jQuery-Autocomplete
	$("#buscar_producto").autocomplete({
		serviceUrl: "productos_autocomplete.php",   
		onSelect: function alSeleccionarProducto(eleccion){
			console.log("Elegiste: ",eleccion);
			if(eleccion.data.unidad_productos == 'KG'){
				$("#precio_mayoreo").val(eleccion.data.precio_mayoreo);
				$("#precio_menudeo").val(eleccion.data.precio_menudeo);
				if($("#mayoreo").prop("checked")){
					precio = eleccion.data.precio_mayoreo;
				}
				else{
					precio = eleccion.data.precio_menudeo;
					
				}
				
				$("#cantidad").val(1);
				$("#precio").val(precio);
				$("#importe").val(eleccion.data.precio_menudeo * 1);
				producto_elegido = eleccion.data;
				
				$("#modal_granel").modal("show");
				$("#buscar_producto").val("");
			}
			else{
				agregarProducto(eleccion.data)
				
				
			}
		},
		autoSelectFirst	:true , 
		showNoSuggestionNotice	:true , 
		noSuggestionNotice	: "Sin Resultados"
	});
	
	
	$('#codigo_producto').keypress( function buscarCodigo(event){
		if(event.which == 13){
			if($(this).val() == ""){
				alertify.error("Escribe un Código");
				return;
			}
			console.log("buscarCodigo()");
			var input = $(this);
			// input.prop('disabled',true);
			input.toggleClass('ui-autocomplete-loading');
			var codigoProducto = $(this).val();
			$.ajax({
				url: "../control/buscar_normal.php",
				dataType: "JSON",
				method: 'POST',
				data: {tabla:'productos', campo:'codigo_productos', id_campo: codigoProducto}
				}).done(function terminabuscarCodigo(respuesta){
				
				if(respuesta.numero_filas >= 1){
					console.log("Producto Encontrado");
					producto_elegido = respuesta.fila;
					
					if(producto_elegido.unidad_productos == 'PZA'){//Si el producto se vende por pieza
						
						producto_elegido.importe= producto_elegido.precioventa_menudeo_productos;
						producto_elegido.cantidad=1 ;
						agregarProducto(producto_elegido);
						$("#codigo_producto").focus();
						
					}
					else if(producto_elegido.unidad_productos == 'KG'){ //Si el producto se vende a granel
						$('#modal_granel').modal('show');
						
						$('#unidad_granel').val(1);
						$('#costo_granel').val(producto_elegido.precio_menudeo);
						$('#costoventa_granel').text('$ '+ producto_elegido.precioventa_menudeo_productos);
						
					}
					$('#form_agregar_producto')[0].reset();		
				}
				else{
					alertify.error('Código no Encontrado');
				}
				
				}).always(function(){
				
				input.toggleClass('ui-autocomplete-loading');
				input.prop('disabled',false);
				input.focus();
			});
		} 
	});
	
	$("#modal_granel").on("shown.bs.modal", function alMostrarGranel() { 
		$("#cantidad").focus();
	});
	$("#modal_pago").on("shown.bs.modal", function alMostrarPago() { 
		$("#efectivo").focus();
	});
	
	$("#cantidad").on("keyup", calcularGranel)
	$("#importe").on("keyup", calcularGranel);
	
	$("input").focus( function selecciona_input(){
		
		$(this).select();
	});
	
	
	$('#cerrar_venta').click( cobrar);
	
	$("#codigo_producto").focus();
}); 

function cobrar(){
	
	if($(".tabla_venta:visible tbody tr").length == 0){
		
		alertify.error('No hay productos');
		return false;
	}
	
	$("#modal_pago").modal("show");
	
	$("#efectivo").val($(".total:visible").val());
	$("#tarjeta").val($(".total:visible").val());
	$("#pago").val($("#efectivo").val());
	$("#pago").focus();
	calculaCambio();
}

function calcularGranel(event){
	let precio = Number($("#precio").val());
	let cantidad = Number($("#cantidad").val());
	console.log("target",event.target.id)
	
	let importe = precio * cantidad;
	
	if(event.target.id == 'cantidad'){ 
		
		$("#importe").val(importe.toFixed(2))
	}
	else{
		importe = Number($("#importe").val());
		cantidad = importe / precio;
		
		$("#cantidad").val(cantidad.toFixed(3))
		
	}
	console.log("importe",importe )
}

function agregarGranel(event){
	event.preventDefault();
	
	producto_elegido.cantidad = $("#cantidad").val();
	$("#modal_granel").modal("hide");
	agregarProducto(producto_elegido);
	
	$("#buscar_producto").focus();
}


function agregarProducto(producto){
	console.log("agregarProducto()", producto);
	
	let articulos = $(".tabla_venta:visible tbody tr").size();
	
	if($("#mayoreo").prop("checked")){
		precio = producto['precio_mayoreo'];
	}
	else{
		precio = producto['precio_menudeo'];
		
	}
	//Buscar por id_productos, si se encuentra agregar 1 unidad sino agregar nuevo producto
	console.log("Buscando id_productos = ", producto.id_productos);
	var $existe= $(".tabla_venta:visible").find(".id_productos[value='"+producto.id_productos+"']");
	console.log("existe", $existe);
	
	if($existe.length > 0){
		console.log("El producto ya existe");
		let cantidad_anterior = Number($existe.closest("tr").find(".cantidad").val());
		console.log("cantidad_anterior", cantidad_anterior)
		cantidad_nueva = Number(cantidad_anterior) + Number(producto.cantidad);
		console.log("cantidad_nueva", cantidad_nueva)
		
		$existe.closest("tr").find(".cantidad").val(cantidad_nueva.toFixed(2));
	}
	else{
		if(!producto['cantidad']){
			
			producto['cantidad'] = 1;
		}
		console.log("El producto no existe, agregarlo a la tabla");
		$fila_producto = `<tr class="bg-info">
		
		
		<td class="col-sm-1">
		<input type="number"  step="any" class="cantidad form-control text-right"  value='${producto['cantidad']}'>
		
		
		<input hidden class="id_productos"  value="${producto['id_productos']}">
		<input hidden class="unidad" value='${producto['unidad_productos']}'>
		<input hidden class="descripcion" value='${producto['descripcion_productos']}'>
		<input hidden class="precio_menudeo" value='${producto['precio_menudeo']}'>
		<input hidden class="precio_mayoreo" value='${producto['precio_mayoreo']}'>
		<input hidden class="precio_dist" value='${producto['precio_dist']}'>
		<input hidden class="precio_fabrica" value='${producto['precio_fabrica']}'>
		<input hidden class="piezas_mayoreo" value='${producto['piezas_mayoreo']}'>
		<input hidden class="piezas_dist" value='${producto['piezas_dist']}'>
		<input hidden class="piezas_fabrica" value='${producto['piezas_fabrica']}'>
		<input hidden class="ganancia_porc" value='${producto['ganancia_menudeo_porc']}'>
		<input hidden class="costo_proveedor" value='${producto['costo_proveedor']}'>
		<input hidden class="porc_descuento" value='${producto['porc_descuento']}'>
		
		</td>
		
		<td class="text-center">${producto['unidad_productos']}</td>
		<td class="text-center">${producto['descripcion_productos']}</td>
		<td class="col-sm-1">
		<input  type="number" class='precio form-control' value='${precio}'>
		<span class="tipo_precio">Precio Público</span>
		</td>
		<td class="col-sm-1"><input readonly type="number" class='importe form-control text-right' > </td>
		<td class="col-sm-1">	
		<input type="number" class="descuento form-control"   value='0'> 
		</td>
		<td class="col-sm-1">	
		<input class="cant_descuento form-control"  > 
		</td>
		<td class="col-sm-1">	
		<input class="existencia_anterior form-control" readonly  value='${producto['existencia_productos']}'> 
		</td>
		<td class="text-center">
		<button title="Eliminar Producto" class="btn btn-danger btn_eliminar">
		<i class="fa fa-trash"></i>
		</button> 
		<label hidden  class="custom_checkbox">
		<input  class="mayoreo" type="checkbox">
		Mayoreo
		<span class="checkmark"></span>
		</label>
		
		</td>
		</tr>`;
		
		resetFondo();
		
		$(".tabla_venta:visible tbody").append($fila_producto);
		
		//Asigna Callbacks de eventos
		$(".mayoreo").change(aplicarMayoreoProducto);
		$(".descuento").change(calcularDescuento);
		$(".descuento").keyup(calcularDescuento);
		
		$(".cant_descuento ").change(calcularDescuento);
		$(".cant_descuento ").keyup(calcularDescuento);
		
		$(".cantidad").keyup(sumarImportes);
		$(".cantidad").change(sumarImportes);
		
		$(".precio").keyup(sumarImportes);
		$(".precio").change(sumarImportes);
		
		$("input").focus(function(){
			$(this).select();
		});
		$(".btn_eliminar").click(eliminarProducto);
		$("#buscar_producto").val("");
		
	}
	
	alertify.success("Producto Agregado")
	
	sumarImportes();
	$('#form_agregar_producto')[0].reset();	
	// $("#codigo_producto").focus();
}


function calcularDescuento(event){
	
	console.log("calcularDescuento()");
	
	let porc_descuento = Number($(".total_descuento:visible").val());
	let subtotal = Number($(".subtotal:visible").val())
	
	ahorro = subtotal * porc_descuento / 100;
	console.log("ahorro", ahorro);
	
	let total = subtotal - ahorro;
	
	$(".total_ahorro:visible").val(ahorro.toFixed(2));
	$(".total:visible").val(total.toFixed(2));
	
	// fila.find(".cant_descuento").val(ahorro.toFixed(2))
	
	// let fila = $(this).closest("tr");
	// let importe = $(fila).find(".importe").val();
	// $(this).val()
	// console.log("event target", event.target);
	
	// if($(event.target).hasClass("cant_descuento") ){
		// let cant_descuento = Number($(this).val())
		// console.log("Descuento por cantidad");
		// porc_descuento = cant_descuento * 100 / importe ;
		// ahorro = cant_descuento;
		// $(fila).find(".descuento").val(porc_descuento.toFixed(2))
		
	// }
	// else{
		// if($(event.target).hasClass("cantidad")){
			// fila.find(".precio").val(obj_precio.precio);
			
		// }
		// else{
			
			
			// console.log("Descuento por porcentaje");
			// console.log("importe: ", importe);
			console.log("porc_descuento" );
			
			// let porc_descuento = Number($(this).val())
			
			// ahorro = importe * porc_descuento / 100;
			// fila.find(".cant_descuento").val(ahorro.toFixed(2))
			
		// }
		
	// }
	
	// sumarImportes();
	
	
}


function sumarImportes(event){
	console.log("sumarImportes()");
	
	let subtotal = 0;
	let descuento = 0;
	let total = 0;
	let articulos = 0;
	let importe = 0;
	let ahorro = 0;
	let porc_descuento = 0;
	let total_descuento = 0;
	
	$(".tabla_venta:visible tbody tr").each(function(indice, item ){
		let fila = $(this);
		let piezas_descuento = Number(fila.find(".piezas_descuento").val());
		let porc_descuento = Number(fila.find(".porc_descuento").val());
		let descuento = Number(fila.find(".descuento").val());
		let cant_descuento = Number(fila.find(".cant_descuento").val());
		let cantidad = Number(fila.find(".cantidad").val());
		
		let obj_producto = {
			"precio_menudeo": fila.find(".precio_menudeo").val(),
			"precio_mayoreo": fila.find(".precio_mayoreo").val(),
			"precio_dist": fila.find(".precio_dist").val(),
			"precio_fabrica": fila.find(".precio_fabrica").val(),
			"piezas_mayoreo": fila.find(".piezas_mayoreo").val(),
			"piezas_dist": fila.find(".piezas_dist").val(),
			"piezas_fabrica": fila.find(".piezas_fabrica").val()
			
			
		};
		
		let obj_precio = tipoPrecio(cantidad, obj_producto);
		
		//Imprime el tipo de precio y el precio unitario dependiento el numero de piezas
		fila.find(".tipo_precio").html(obj_precio.tipo_precio);
		
		
		/*
		if(event){
			
			console.log("event target", event.target);
			
			if($(event.target).hasClass("cant_descuento") ){
				
				// console.log("Descuento por cantidad");
				porc_descuento = cant_descuento * 100 / importe ;
				ahorro = cant_descuento;
				$(fila).find(".descuento").val(porc_descuento.toFixed(2))
				
			}
			else{
				
				// if(obj_precio.precio > 0){
				
				// fila.find(".precio").val(obj_precio.precio);
				// }
				if($(event.target).hasClass("cantidad")){
					//Descuento por piezas de mayoreo
					console.log("Descuento por piezas de mayoreo")
					if(obj_precio.precio > 0){
						fila.find(".precio").val(obj_precio.precio);
					}
				}
				else{
					
					// console.log("Descuento por porcentaje");
					// console.log("importe: ", importe);
					// console.log("porc_descuento", descuento);
					
					// ahorro = importe * descuento / 100;
					// fila.find(".cant_descuento").val(ahorro.toFixed(2))
					
				}
				
			}
			
		}
		
		*/
		
		let precio =  Number(fila.find(".precio").val());
		
		importe= cantidad * precio;
		subtotal+= importe;
		
		descuento = ahorro;
		// total_descuento+= ahorro;
		// total_descuento+= ahorro;
		
		// if(cantidad >= piezas_descuento){
		// console.log("Aplicar promocion");
		// descuento = porc_descuento;
		// }
		// else{
		// console.log("No Aplica promocion");
		// descuento = 0;
		
		// }
		
		
		
		
		//Si la unidad es a granel solo contar 1 articulo
		if($(this).find(".unidad").val() == 'KG'){
			articulos+= 1;
		}
		else{
			articulos+= Math.round(cantidad);
		}
		
		
		$(this).find(".importe").val(importe.toFixed(2))
		
	});
	
	
	
	// $(".articulos:visible").val(articulos);
	// $(".subtotal:visible").val(round(subtotal, 0.5).toFixed(2));
	// $(".total_descuento:visible").val(round(total_descuento, 0.5).toFixed(2));
	// $(".total:visible").val(round(total, 0.5).toFixed(2));
	// $("#efectivo").val(round(total, 0.5).toFixed(2));
	// $("#total_pago").val(round(total, 0.5).toFixed(2));
	
	
	$(".nav-tabs .active .badge").text(articulos);
	$(".articulos:visible").val(articulos);
	$(".subtotal:visible").val(subtotal.toFixed(2));
	
	// calcularDescuento();
	
	let total_ahorro = Number($(".total_ahorro:visible").val())
	
	//preguntar sobre redondeo
	
	total = subtotal - total_ahorro;
	
	// $(".total_descuento:visible").val(total_descuento.toFixed(2));
	$(".total:visible").val(total.toFixed(2));
	$("#efectivo").val(total.toFixed(2));
}

function tipoPrecio(cantidad, producto){
	console.log("tipoPrecio()", "Cantidad: ", cantidad, "Producto: ", producto);
	
	switch (true) {
		case (cantidad >= producto.piezas_fabrica && producto.piezas_fabrica > 0):
		console.log("Fabrica");
		tipo_precio = "Precio de Fábrica";
		precio = producto.precio_fabrica;
		break; 
		case (cantidad >= producto.piezas_dist && cantidad  < producto.piezas_fabrica):
		console.log("Distribuidor"); 
		tipo_precio = "Precio Distribuidor";
		precio = producto.precio_dist;
		break;
		case (cantidad >= producto.piezas_mayoreo && cantidad  < producto.piezas_dist):
		console.log("Distribuidor"); 
		tipo_precio = "Precio Mayoreo";
		precio = producto.precio_mayoreo;
		break;
		default:
		
		console.log("Precio Público");
		tipo_precio = "Precio Público";
		precio = producto.precio_menudeo;
		break;
	}
	
	console.log("precio",precio)
	console.log("tipo_precio",tipo_precio)
	
	return {"precio" : precio, "tipo_precio": tipo_precio} ;
	
}

function guardarVenta(event){
	// event.preventDefault();
	console.log("guardarVenta", event.type);
	console.log("event", event);
	console.log("target", event.target);
	
	var boton = $(this).find(":submit");
	var icono = boton.find('.fa');
	var articulos = $("#tabla_venta tbody tr").size();
	var productos = [];
	var task = $.Deferred();
	
	// Si el evento es por F12 cobrar o F6 Pendiente
	if(event.type == "submit"){
		console.log("Cobrar");
		
		var estatus_ventas ="PAGADO" ;
		var nombre_cliente =  $("#tabs_ventas li.active a .nombre_cliente").val()
		event.preventDefault();
		
	}
	else{
		console.log("Pendiente");
		var estatus_ventas ="PENDIENTE" ;
		var nombre_cliente =  event;
		
	}
	
	
	if($("#id_vendedores").val() == ''){
		
		alertify.error("Elige un vendedor");
		
		task.reject();
		return task.promise();
	}
	
	
	boton.prop('disabled',true);
	icono.toggleClass('fa-check fa-spinner fa-spin');
	
	//Agrega los productos al array que se envia
	$(".tabla_venta:visible tbody tr").each(function(index, item){
		productos.push({
			"id_productos": $(item).find(".id_productos").val(),
			"cantidad": $(item).find(".cantidad").val(),
			"precio": $(item).find(".precio").val(),
			"descripcion": $(item).find(".descripcion").val(),
			"importe": $(item).find(".importe").val(),
			"existencia_anterior": $(item).find(".existencia_anterior").val(),
			"costo_proveedor": $(item).find(".costo_proveedor").val()
			
		})
	});
	
	return $.ajax({
		url: 'guardar.php',
		method: 'POST',
		dataType: 'JSON',
		data:{
			id_ventas: $('#tabs_ventas li.active').find(".id_ventas").val(),
			id_usuarios: $('#id_vendedores').val(),
			id_turnos:$('#id_turnos').val(),
			forma_pago:$('.forma_pago:checked').val(),
			articulos: $(".articulos:visible").val(),
			"productos": productos, 
			"efectivo": $("#efectivo").val(),
			"estatus_ventas": estatus_ventas,
			"nombre_cliente": nombre_cliente.toUpperCase(),
			subtotal: $(".subtotal:visible").val(),
			total_descuento: $(".total_descuento:visible").val(),
			total_ventas: $(".total:visible").val()
		}
		}).done(function(respuesta){
		if(respuesta.estatus_venta == "success"){
			alertify.success('Venta Guardada');
			//Resetea la venta
			
			
			$("#modal_pago").modal("hide");
			
			limpiarVenta();
			
			// console.log("Venta Activa", $("#tabs_ventas>li.active input").val("Mostrador"));
			// imprimirTicket( respuesta.id_ventas)
			
		}
		}).fail(function(xhr, error, errnum){
		alertify.error('Ocurrio un error' + error);
		}).always(function(){
		boton.prop('disabled',false);
		icono.toggleClass('fa-check fa-spinner fa-spin');
	});
	
	TotalTurno();
}



function limpiarVenta(){
	console.log("limpiarVenta()");
	//resetea los datos de la ultima venta 
	var num_cliente = $("#tabs_ventas li.active").index() + 1;
	$("#tabs_ventas li.active a .nombre_cliente").val("Cliente " + num_cliente);
	$(".tabla_venta:visible tbody tr").remove(); //Quita las filas de productos
	$(".tabla_venta:visible tbody tr").remove(); //Quita las filas de productos
	$("li.cremeria.active").remove(); //Quita la venta si es de cremeria
	$(".tab-pane.cremeria.active").remove(); //Quita la venta si es de cremeria
	
	$("#tabs_ventas a").first().click();
	
	$("#codigo_productos").focus();
	sumarImportes();
}

function eliminarProducto(){
	$(this).closest("tr").remove();
	sumarImportes();
}

$("input").focus(function(){
	$(this).select();
	
});




function imprimirTicket(id_ventas){
	console.log("imprimirESCPOS()");
	
	$.ajax({
		url: "imprimir_ticketpos.php" ,
		data:{
			"id_ventas" : id_ventas
		}
		}).done(function (respuesta){
		
		printService.submit({
			'type': 'LABEL',
			'raw_content': respuesta
		});
		}).always(function(){
		
	});
}



function beforePrint() {
	//Antes de Imprimir
}
function afterPrint() {
	window.location.reload(true);
	
	if(tipo_ticket == "cliente"){
		// tipo_ticket = "copia";
		// window.print();
	}
	else{
		
		// window.location.reload(true);
	}
	
	limpiarVenta();
}


function disableFunctionKeys(e) {
	var functionKeys = new Array(112, 113, 114, 115,117, 118, 119, 120, 121, 122, 123);
	if (functionKeys.indexOf(e.keyCode) > -1 || functionKeys.indexOf(e.which) > -1) {
		e.preventDefault();
		
		console.log("key", e.which)
		console.log("Tecla", e.key)
		console.log("Codigo", e.keyCode)
		
	}
	
	if(e.key == 'F12'){
		
		console.log("F12");
		
		$("#cerrar_venta").click()
	}
	
	if(e.key == 'F10'){
		console.log("F10");
		$("#buscar_producto").focus()
	}
	if(e.key == 'F6'){
		$("#btn_pendiente").click();
	}
	if(e.key == 'F4'){
		$("#btn_refresh").click();
	}
	
	if(e.key == 'F11'){
		console.log("F11");
		$("#mayoreo").click();
	}
	
	if(e.key == 'Escape'){
		
		console.log("ESC");
		
		$("#codigo_producto").focus()
	}
	// $input_activo = $(this);
};

function aplicarMayoreoGeneral(){
	var $precio;
	console.log("aplicarMayoreo");
	
	$(".tabla_venta:visible tbody tr").each(function(index, item){
		if($("#mayoreo").prop("checked")){
			$precio =  $(item).find(".precio_mayoreo").val();
		}
		else{
			$precio =  $(item).find(".precio_menudeo").val();
		}
		$(item).find(".precio").val($precio);
	});
	
	sumarImportes();
}
function aplicarMayoreoProducto(){
	var $precio;
	var fila =  $(this).closest("tr");
	console.log("aplicarMayoreoProducto");
	
	
	if($(this).prop("checked")){
		$precio = fila.find(".precio_mayoreo").val();
	}
	else{
		$precio =  fila.find(".precio_menudeo").val();
	}
	fila.find(".precio").val($precio);
	
	
	sumarImportes();
}

function aplicarDescuento(){
	console.log("aplicarDescuento");
	
	// var fila =  $(this).closest("tr");
	
	// var importe = Number(fila.find(".importe").val());
	// var porc_descuento = Number(fila.find(".descuento").val());
	
	// var ahorro = importe * porc_descuento / 100;
	
	// fila.find(".cant_descuento").val(ahorro.toFixed(2));
	
	sumarImportes();
}

//Funciona a llamar si ha terminado de imprimir
if (window.matchMedia) {
	var mediaQueryList = window.matchMedia('print');
	mediaQueryList.addListener(function(mql) {
		if (mql.matches) {
			beforePrint();
		} 
		else {
			afterPrint();
		}
	});
}

// window.onbeforeprint = beforePrint;
//window.onafterprint = afterPrint;


function resetFondo(){
	
	$("#tabla_venta tbody tr").removeClass("bg-info");
	
}

function navegarFilas(e){
	var $table = $(this);
	var $active = $('input:focus,select:focus',$table);
	var $next = null;
	var focusableQuery = 'input:visible,select:visible,textarea:visible';
	var position = parseInt( $active.closest('td').index()) + 1;
	console.log('position :',position);
	switch(e.keyCode){
		case 37: // <Left>
		$next = $active.parent('td').prev().find(focusableQuery);   
		break;
		case 38: // <Up>                    
		$next = $active
		.closest('tr')
		.prev()                
		.find('td:nth-child(' + position + ')')
		.find(focusableQuery)
		;
		
		break;
		case 39: // <Right>
		$next = $active.closest('td').next().find(focusableQuery);            
		break;
		case 40: // <Down>
		$next = $active
		.closest('tr')
		.next()                
		.find('td:nth-child(' + position + ')')
		.find(focusableQuery)
		;
		break;
	}    
	if($next && $next.length)
	{        
		$next.focus();
	}
}									

//--- Calcular Cambio: Pestaña "Efectivo"
$("#pago").keyup(calculaCambio);

//--- Calcular Comisión: Pestaña "Tarjeta" (PENDIENTE)
function CalcularComision (){
	// $("#tarjeta").val();
	let debito = $("#debito").val();
	let credito = $("#credito").val();
	alert(this.value);
	// let cambio = efectivo - total;
	// $("#cambio").val(cambio);
};

function TotalTurno (){
	console.log();
	$.ajax({
		url: "../funciones/total_turno.php",
		dataType: "JSON"
		}).done(function (respuesta){
		console.log(respuesta);
		if (respuesta.total_turno > 3000){
			alertify.warning("Límite $3000 Superado");
		};
	});
}

function calculaCambio(){
	let efectivo = $("#efectivo").val();
	let pago = $("#pago").val();
	let cambio = pago - efectivo;
	$("#cambio").val(cambio);
}	