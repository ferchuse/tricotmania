$(document).ready(function() {
   
	$('#certificado').fileupload({
			//loadImageMaxFileSize: 1000,
		dataType: 'json',
		done: function (e, data) {
			$.each(data.result.files, function (index, file) {
				if(file.error){
					alertify.error("Error, el tamaño máximo es de 2MB, intenta nuevamente.");
					$("#barra_cer.progress").addClass("hide");
				}
				else{
					//Carga Existosa
					
					$("#url_certificado").val(file.url);
					
					$("#nombre_cer").html(file.name);
					$("#mensaje_cer").removeClass("hide");
					$("#barra_cer").removeClass("hide");
					
					$.ajax({
						url : "control/decrypt_certificado.php",
						method: "POST",
						data : {
								url_certificado : file.url,
								tipo : 'cer'
						}
						
					}).done(function(respuesta){
					
						if(respuesta.pem.result == 1){
							alertify.success("Archivo PEM Generado Correctamente");
							$("#rfc_emisores").val(respuesta.datos_certificado.rfc);
							$("#razon_social_emisores").val(respuesta.datos_certificado.razon_social);
						}else{
							alertify.success("Error al generar archivo PEM");
						}
					});
				
				}
				
				
			});
		},
		progressall: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
			$("#barra_cer.progress-bar").css("width" , progress +"%");
			$("#barra_cer.progress-bar").html(progress +"%");
		},
		fail: function(e, data){
			alertify.error("Ocurrio un Error, vuelve a intentar");
		}
	});	
	
	$('#llave_privada').fileupload({
		dataType: 'json',
		done: function (e, data) {
			$.each(data.result.files, function (index, file) {
				if(file.error){
					alertify.error("Error, el tamaño máximo es de 2MB, intenta nuevamente.");
					$("#barra_key.progress").addClass("hide");
				}
				else{
					//Carga Existosa
				
					if($("#rfc_emisores").val() == ""){
							alertify.error("Primero debes cargar el certificado");
							return false;
					}
					
						
					$("#url_certificado").val(file.url);
					$("#nombre_key").html(file.name);
					$("#mensaje_key").removeClass("hide");
					$("#barra_key").addClass("hide");
					
					alertify.prompt("Escribe Contraseña","Escribe la Contraseña de la Llave Privada", "", decrypt_key, cancel_pass );
					
					
					function cancel_pass(){
						console.log("cancel_pass()");
						
					}
					function decrypt_key(evt_alert , password){
						console.log("evt_alert()");
						console.log(evt_alert);
						$("#password").val(password);
						$.ajax({
							url : "control/decrypt_certificado.php",
							method: "POST",
							data : {
									url_certificado : file.url,
									tipo : 'key',
									'password' : password,
									'rfc' : $("#rfc_emisores").val()
							}
							
						}).done(function(respuesta){
							//TODO si resultado api es == rfc:clave
							if(respuesta.pem.result == 1){
								alertify.success("Archivo Key Generado Correctamente");
								
							}else{
								alertify.error(respuesta.pem.error);
							}
						});
				
					}
				}
				
				console.log("---------data.textStatus.---------");
				console.log(data.textStatus);
				console.log("---------data.textStatus.---------");
				console.log(file);
				//$('<p/>').text(file.name).appendTo(document.body);
				console.log("FileUpload Complete"); 
			});
		},
		progressall: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
			$("#barra_key.progress-bar").css("width" , progress +"%");
			$("#barra_key.progress-bar").html(progress +"%");
		},
		fail: function(e, data){
			alertify.error("Ocurrio un Error, vuelve a intentar");
		}
	});
	 
	$("#form_emisores").submit(guardarUsuario);
		
  
		
});

function guardarUsuario(event){
		event.preventDefault();
		console.log("guardarUsuario");
		var $boton = $("#form_emisores").find(":submit")
		var $icono = $boton.find(".fa");
		
		$boton.prop("disabled", true);
		$icono.toggleClass("fa-save fa-spinner fa-spin");
		
		
			$.ajax({
				url: "control/guardar_emisor.php",
				method: "POST",
				data: $("#form_emisores").serialize()
			
			}).done( function afterGuardar(respuesta){
				
					if(respuesta.estatus == "success"){
						
						alertify.success("Agregado Correctamente");
						window.location.href = "index.php";
					}
					else{
							alertify.error("Ocurrio un error");
					}
					
			}).fail(function(xhr, error, errnum){
				alertify.error("Ocurrio un error" + error);
				
			}).always(function(){
				$boton.prop("disabled", false);
				$icono.toggleClass("fa-save fa-spinner fa-spin");
		
			});
			
	}