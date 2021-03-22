<?php 
	
	 // echo var_dump($certificado->generaCerPem("GUAF880601NA6.cer"));
	// echo var_dump($certificado->validarCertificado("GUAF880601NA6.cer.pem"));
	// echo var_dump($certificado->getFechaVigencia("GUAF880601NA6.cer.pem"));

	
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Nuevo Usuario</title>

	<?php include("styles.php");?>
	<link href="css/fileupload.css" rel='stylesheet' type='text/css'>
	  
</head>
<body>
<form id="form_emisores">
<div class="container">
	<div class="row">
		<div class="col-sm-12">
			 <legend>Nuevo Usuario</legend>
			 <div class="row">
				<div class="form-group col-md-6">
						<span class="btn btn-success fileinput-button btn-block">
							<i class="fa fa-upload"></i>
							<label >CSD-Certificado .cer</label>  
							<input id="certificado" type="file" accept=".cer" name="files[]" data-url="control/fileupload.php" >
							
						</span>
						<div id="mensaje_cer" class="hide alert alert-success text-center">
							<strong><i class="fa fa-check"></i> Archivo <span id="nombre_archivo"> </span> cargado correctamente</strong> 
						</div>
						<div class="progress hidden" id="barra_cer">
							<div class="progress-bar progress-bar-striped active" >
							</div>
						</div>					 
				</div>
				<div class="form-group col-md-6">
						<span class="btn btn-primary fileinput-button btn-block">
							<i class="fa fa-upload"></i>
							<label >Llave Privada .key</label>  
							<input id="llave_privada" type="file" accept=".key" name="files[]" data-url="control/fileupload.php" >
						</span>
						<div id="mensaje_key" class="hide alert alert-success text-center">
							<strong><i class="fa fa-check"></i>Archivo <span id="nombre_archivo"> </span> cargado correctamente</strong> 
						</div>
						<div class="progress hidden" id="barra_key">
							<div class="progress-bar progress-bar-striped active" >
							</div>
						</div>					 
				</div>
			</div>
			<div class="form-group ">
				<label for="">RFC:</label>
				<input  readonly type="text" class="form-control" name="rfc_emisores" id="rfc_emisores"  >
			</div>
			<div class="form-group  hidden">
				<label for="">Tipo de Persona:</label>
				<div class="radio">
					<label><input  type="radio" class="form-control" name="tipo_persona" >Física</label>
				</div>
				<div class="radio">
					<label><input  type="radio" class="form-control" name="tipo_persona" >Moral</label>
				</div>
			</div>
			<div class="form-group">
				<label for="">Razón Social: </label>
				<input  readonly type="text" class="form-control" name="razon_social_emisores" id="razon_social_emisores"  >
			</div>
			<div class="form-group ">
				<label for="">Lugar de Expedición:</label>
				<input required type="text" class="form-control" name="lugar_expedicion_emisores" id="lugar_expedicion_emisores"  >
			</div>
			<div class="form-group ">
				<label for="">
					Contraseña:*
				</label>
				<input required type="text" class="form-control" name="password" id="password"  >
			</div>
			<div class="form-group ">
				<label for="">
					Correo:*
				</label>
				<input required type="text" class="form-control" name="correo_emisores" id="correo_emisores"  >
			</div>
			<div class="form-group ">
					<label class="control-label" for="regimen_emisores">
						Régimen fiscal
						<span class="requerido">*</span>:
					</label>
					<select id="regimen_emisores" required name="regimen_emisores" class="form-control">
						<option value="">Seleccione...</option>
						<option value="601">601	General de Ley Personas Morales</option>
						<option value="603">603	Personas Morales con Fines no Lucrativos</option>
						<option value="605">605	Sueldos y Salarios e Ingresos Asimilados a Salarios</option>
						<option value="606">606	Arrendamiento</option>
						<option value="607">607	Régimen de Enajenación o Adquisición de Bienes</option>
						<option value="608">608	Demás ingresos</option>
						<option value="609">609	Consolidación</option>
						<option value="610">610	Residentes en el Extranjero sin Establecimiento Permanente en México</option>
						<option value="610">611	Ingresos por Dividendos (socios y accionistas)</option>
						<option value="612">612	Personas Físicas con Actividades Empresariales y Profesionales</option>
						<option value="614">614	Ingresos por intereses</option>
						<option value="616">616	Sin obligaciones fiscales</option>
						<option value="620">620	Sociedades Cooperativas de Producción que optan por diferir sus ingresos</option>
						<option value="621">621	Incorporación Fiscal</option>
						<option value="622">622	Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras</option>
						<option value="623">623	Opcional para Grupos de Sociedades</option>
						<option value="624">624	Coordinados</option>
						<option value="628">628	Hidrocarburos</option>
						<option value="629">629	De los Regímenes Fiscales Preferentes y de las Empresas Multinacionales</option>
						<option value="630">630	Enajenación de acciones en bolsa de valores</option>
						<option value="615">615	Régimen de los ingresos por obtención de premios</option>
					</select>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<button class="btn btn-success btn-lg pull-right" type="submit" >
				<i class="fa fa-save"></i> Guardar 
			</button>
		</div>
	</div>
	
</div>
</form>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/alertify.min.js"></script>
<script src="lib/date.js"></script>
<script src="js/common.js"></script>
<script src="lib/date.js"></script>

<script src="lib/fileupload.widget.js"></script>
<script src="lib/fileupload.iframe-transport.js"></script>
<script src="lib/fileupload.js"></script>

<script src="js/nuevo_usuario.js"></script>

</body>
</html>