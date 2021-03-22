$(document).ready(function(){
	$('#btn-altaNew').click(function(){
		$('#form_nuevo_notification')[0].reset();
		$('#modal_nuevo_notification').modal('show');
		console.log("modal_alta_mensaje");
	});

	$('#form_nuevo_notification').submit(function enviar_formulario(event){
		event.preventDefault();
		var formulario = $('#form_nuevo_notification').serialize();
		console.log(formulario);
		$.ajax({
			url: 'control/push_notification.php',
			method: 'POST',
			dataType: 'JSON',
			data: formulario
		}).done(function(message_status){
			alertify.success('Se ha enviado el mensaje');
		});
	});
	});