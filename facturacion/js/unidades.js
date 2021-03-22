$(document).ready(function() {
    var filtros = $("#form_filtros").serialize();
    cargarTabla(filtros);
		
		
	$(".filtro").change(function(){
		filtros = $("#form_filtros").serialize();
		cargarTabla(filtros);
		
	});
	
	
	$( "#buscar_unidades" ).autocomplete({
		source: "control/search_json.php?tabla=unidades&campo=nombre_unidades&valor=nombre_unidades&etiqueta=nombre_unidades&extra_labels[]=id_unidades",
		minLength : 2,
		autoFocus: true,
		select: function seleccionaAlumno( event, ui ) {
			console.log("Unidad Seleccionada");
			console.log(ui.item.extras);
			var id_unidades = (ui.item.extras.id_unidades);
			var id_emisores = $("#id_usuarios").val();
			
			// insertar a unidades_emisor el id_unidad y id_emisor
			$( "#buscar_unidades" ).val("");
			
			$.ajax({
				url: "control/fila_insert.php",
				method: "POST",
				data:{  
						tabla: "unidades_emisor",
						valores:[
							{name: "id_unidades" , value : id_unidades},
							{name: "id_emisores" , value : $("#id_usuarios").val()}
						]					
				}
			
			}).done( function afterInsertCatalogo(respuesta){
				
					if(respuesta.estatus == "success"){
						alertify.success("Agregado Correctamente");
							cargarTabla(filtros)
					}
					
			});
			
		
		}
	});	
		
    //--------FILTRO--------
    function cargarTabla(filtros) {
        var cargador = "<tr><td class='text-center' colspan='5'><i class='fa fa-spinner fa-spin fa-3x'></i></td></tr>";
        $('#lista_unidades').html(cargador);
        $.ajax({
            url: 'control/lista_unidades.php',
            method: 'GET',
            data: {id_emisores : $("#id_usuarios").val()}
        }).done(function(respuesta) {
            $('#lista_unidades').html(respuesta);

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
											tabla: 'unidades_emisor',
											id_campo: 'id_unidades_emisor',
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