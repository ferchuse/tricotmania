$(document).ready(function() {
    var filtros = $("#form_filtros").serialize();
    cargarTabla(filtros);
		
		
	$(".filtro").change(function(){
		filtros = $("#form_filtros").serialize();
		cargarTabla(filtros);
		
	});
	
	
	$( "#buscar_productos" ).autocomplete({
		source: "control/search_json.php?tabla=productos&campo=descripcion_productos&valor=descripcion_productos&etiqueta=descripcion_productos&extra_labels[]=id_productos&order_by=descripcion_productos&campo_2=id_productos",
		minLength : 2,
		autoFocus: true,
		select: function seleccionaAlumno( event, ui ) {
			console.log("Unidad Seleccionada");
			console.log(ui.item.extras);
			$("#id_productos").val(ui.item.extras.id_productos);
			var id_emisores = $("#id_usuarios").val();
			
			$("#form_productos").submit();
			// insertar a productos_emisor el id_unidad y id_emisor
			$( "#buscar_productos" ).val("");
			
		}
	});	
	
	$("#form_productos").submit( function(event){
		event.preventDefault();
		
		
			$.ajax({
				url: "control/fila_insert.php",
				method: "POST",
				data:{  
						tabla: "productos_emisor",
						valores:[
							{name: "id_productos" , value : $("#id_productos").val()},
							{name: "id_emisores" , value : $("#id_usuarios").val()}
						]					
				}
			
			}).done( function afterInsertCatalogo(respuesta){
				
					if(respuesta.estatus == "success"){
						$("#form_productos")[0].reset();
						alertify.success("Agregado Correctamente");
							cargarTabla(filtros)
							
					}
					else{
							alertify.error("Ocurrio un error");
					}
					
			}).fail(function(xhr, error, errnum){
				alertify.error("Ocurrio un error" + error);
				
			}).always(function(){
			
			});
			
	});
		
    //--------FILTRO--------
    function cargarTabla(filtros) {
        var cargador = "<tr><td class='text-center' colspan='5'><i class='fa fa-spinner fa-spin fa-3x'></i></td></tr>";
        $('#lista_productos').html(cargador);
        $.ajax({
            url: 'control/lista_productos.php',
            method: 'GET',
            data: {id_emisores : $("#id_usuarios").val()}
        }).done(function(respuesta) {
            $('#lista_productos').html(respuesta);

            //----------MODIFICAR ESTATUS--------
            $('.btn_eliminar').click(function btnBaja() {
                var boton = $(this);
                boton.prop('disabled', true);
                icono = boton.find(".fa");
                icono.toggleClass("fa-trash fa-spinner fa-spin");
                var id_value = boton.data('id_value');
                var fila = boton.closest('tr');
								
								alertify.confirm('Confirmacion', '¿Deseas eliminarlo?', baja, function() {
                    icono.toggleClass("fa-trash fa-spinner fa-spin");
                    boton.prop('disabled', false);
                });
								
                function baja(evet,value) {
									$.ajax({
										url: 'control/fila_delete.php',
										method: 'POST',
										data:{
											tabla: 'productos_emisor',
											id_campo: 'id_productos_emisor',
											id_valor: id_value,
											
										}
									}).done(function(respuesta){
										if(respuesta.estatus == "success"){
											alertify.success("Eliminado Correctamente"); 
											fila.fadeOut(1000);
										}
										else{
											alertify.error("Ocurrio un error"); 
										}
										
									}).fail(function(xhr, error, ernum){
										alertify.error("Ocurrio un error" + error); 
									
									}).always(function(){
										icono.toggleClass("fa-trash fa-spinner fa-spin");
										boton.prop('disabled', false);
										
									
									});
                }
            });
        });
    }
		
		
		$(".exportar").click(function(){
		
			$('#tabla_reporte').tableExport(
			{
				type:'excel',
				tableName:'Reporte', 
				ignoreColumn: [5],
				escape:'false'
			});
		});
		
		
});