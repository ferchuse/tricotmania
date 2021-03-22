<?php
header("Content-Type: application/json");
require 'lib/phpmailer/PHPMailerAutoload.php';
error_reporting(E_ERROR | E_WARNING | E_PARSE);
$respuesta = array();
session_start();

$correo_clientes = isset($_GET["correo"]) ? $_GET["correo"] : "sistemas@glifo.mx";
$url_xml = isset($_GET["url_xml"]) ? $_GET["url_xml"] : 'timbrados/A2000.xml';
$url_pdf = isset($_GET["url_pdf"]) ? $_GET["url_pdf"] : 'timbrados/A2000.pdf';

$nombre_emisor = $_SESSION["razon_social_emisores"];

$mail = new PHPMailer;
$mail->CharSet = 'UTF-8';
// $mail->Encoding = 'base64';

// $mail->isSMTP();                                    
// $mail->Host = 'smtp.live.com';  
// $mail->SMTPAuth = true;                              
// $mail->Username = 'facturacion@glifo.mx';                
// $mail->Password = 'glifo951';                            
// $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
// $mail->Port = 587;                                    
// $mail->SMTPDebug = 0;                            //Activa depuracion SMTP

$mail->setFrom('facturacion@glifo.micrositio.mx', 'Facturacion Glifo Media');
// $mail->addAddress($correo_clientes);     // Destinatario
// $mail->addBCC("contacto@innovaasesoria.com");     // Copia Oculta
$lista_correos = explode("," ,$correo_clientes ) ;
$respuesta["lista_correos"] = $lista_correos;
foreach($lista_correos as $index => $correo){
	$mail->addAddress($correo); 
}
$mail->addBCC("sistemas@glifo.mx"); 
// $mail->addBCC("colegiocovarrubias@gmail.com");     //  Copia Oculta

$mail->addReplyTo("sistemas@glifo.mx", "Glifo Media");      // Add attachments
$mail->addAttachment($url_xml);        // Add attachments
$mail->addAttachment($url_pdf);         // Add attachments
// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Facturación Glifo Media';
$mail->Body    = "<center><b>$nombre_emisor le envia la factura $url_pdf</b> </center>
<hr>
<small><a href='www.glifo.mx'>glifo.mx</a></small>
";
$mail->AltBody = "Adjunto Factura  ";

if(!$mail->send()) {
		$respuesta["estatus_correo"] = "error";
		$respuesta["mensaje_correo"] = 'No se envio el correo.'. $mail->ErrorInfo;
		 
} else {
		$respuesta["estatus_correo"] = "success";
		$respuesta["mensaje_correo"] = "Correo Enviado Correctamente";
		 
}

echo json_encode($respuesta);
?>