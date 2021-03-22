<?php
	include("login/login_success.php");
	include_once("control/is_selected.php");
	include_once("conexi.php");
	$link = Conectarse();
	$menu_activo = "configuracion";
	
	$q_emisor = "SELECT * FROM emisores";
	
	$result_emisor = mysqli_query($link,$q_emisor );
	
	if($result_emisor){
		while($fila_emisor = mysqli_fetch_assoc($result_emisor)){
				extract($fila_emisor);
		
		}		
	}
	else{
		
	}
	
	
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Configuración</title>

	<?php include("styles.php");?>
	
  </head>
  <body>

  <div class="container-fluid">
		<?php include("menu.php");?>
	</div>
	
	<h3 class="text-center">Configuración de Emisor</h3>
	
	
	<div class="container-fluid"  > 
		<div class="row">
			<div class="col-sm-4 col-sm-offset-4" >
				<form id="form_correo" class="form" >
					<div class="form-group">
							<label for="id_niveles">RAZON SOCIAL:</label>
							<input  type="text" required name="razon_social_emisores" id="correo" class="form-control" value="<?php echo $razon_social_emisores;?>">
					</div>
					<div class="form-group">
							<label for="id_niveles">RFC:</label>
							<input  type="text" required name="rfc_emisores" id="rfc_emisores" class="form-control" value="<?php echo $rfc_emisores;?>">
					</div>
					<div class="form-group">
							<label for="id_niveles">Contraseña:</label>
							<input  type="password" required placeholder="Ingresa tu contraseña" name="pass_emisores" id="pass_emisores" class="form-control" >
							<input  type="password" required placeholder="Repite la contraseña" id="pass_emisores2" class="form-control" >
					</div>
					<div class="form-group">
							<label for="id_niveles">Régimen:</label>
							<select id="regimen_emisores" required name="regimen_emisores" class="form-control">
								<option value="">Seleccione...</option>
								<option <?php echo is_selected($regimen_emisores, "601");?> value="601">601	General de Ley Personas Morales</option>
								<option <?php echo is_selected($regimen_emisores, "603");?> value="603">603	Personas Morales con Fines no Lucrativos</option>
								<option <?php echo is_selected($regimen_emisores, "605");?> value="605">605	Sueldos y Salarios e Ingresos Asimilados a Salarios</option>
								<option <?php echo is_selected($regimen_emisores, "606");?> value="606">606	Arrendamiento</option>
								<option <?php echo is_selected($regimen_emisores, "607");?> value="607">607	Régimen de Enajenación o Adquisición de Bienes</option>
								<option <?php echo is_selected($regimen_emisores, "608");?>  value="608">608	Demás ingresos</option>
								<option <?php echo is_selected($regimen_emisores, "609");?> value="609">609	Consolidación</option>
								<option <?php echo is_selected($regimen_emisores, "610");?> value="610">610	Residentes en el Extranjero sin Establecimiento Permanente en México</option>
								<option <?php echo is_selected($regimen_emisores, "611");?> value="611">611	Ingresos por Dividendos (socios y accionistas)</option>
								<option <?php echo is_selected($regimen_emisores, "612");?> value="612">612	Personas Físicas con Actividades Empresariales y Profesionales</option>
								<option <?php echo is_selected($regimen_emisores, "614");?> value="614">614	Ingresos por intereses</option>
								<option <?php echo is_selected($regimen_emisores, "615");?> value="615">615	Régimen de los ingresos por obtención de premios</option>
								<option <?php echo is_selected($regimen_emisores, "616");?> value="616">616	Sin obligaciones fiscales</option>
								<option <?php echo is_selected($regimen_emisores, "620");?> value="620">620	Sociedades Cooperativas de Producción que optan por diferir sus ingresos</option>
								<option <?php echo is_selected($regimen_emisores, "621");?> value="621">621	Incorporación Fiscal</option>
								<option <?php echo is_selected($regimen_emisores, "622");?> value="622">622	Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras</option>
								<option <?php echo is_selected($regimen_emisores, "623");?> value="623">623	Opcional para Grupos de Sociedades</option>
								<option <?php echo is_selected($regimen_emisores, "624");?> value="624">624	Coordinados</option>
								<option <?php echo is_selected($regimen_emisores, "628");?> value="628">628	Hidrocarburos</option>
								<option <?php echo is_selected($regimen_emisores, "629");?> value="629">629	De los Regímenes Fiscales Preferentes y de las Empresas Multinacionales</option>
								<option <?php echo is_selected($regimen_emisores, "630");?> value="630">630	Enajenación de acciones en bolsa de valores</option>
							</select>
					</div>
					
					<div class="form-group">
							<label for="id_niveles">Lugar de Expedición:</label>
							<input  type="text" required name="lugar_expedicion_emisores" id="lugar_expedicion_emisores" class="form-control" value="<?php echo $lugar_expedicion_emisores;?>">
					</div>
					<div class="form-group">
							<label for="id_niveles">Certificado:</label>
							<input  type="text" required name="url_certificado_emisores" id="url_certificado_emisores" class="form-control" >
					</div>
					<div class="form-group">
							<label for="id_niveles">LLave Privada:</label>
							<input  type="text" required name="url_llave_privada_emisores" id="url_llave_privada_emisores" class="form-control" >
					</div>
					<button class="btn btn-success pull-right">
						<i class="fa fa-save"></i>
						Guardar
					</button>
				</form>
			</div>
		</div>
	</div>

	
	<form id="form_correo" class="form" >
	<div id="modal_correo" class="modal fade" role="dialog">
		<div class="modal-dialog modal-sm">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-center"></h4>
				</div>
			 
				<div class="modal-body">
					
						<div class="form-group">
							<label for="id_niveles">Correo:</label>
							<input  type="email" required name="correo" id="correo" class="form-control" >
							<input type="hidden" name="url_xml" id="url_xml" class="form-control" >
							<input type="hidden" name="url_pdf" id="url_pdf" class="form-control" >
						</div>
				</div>
			 
				<div class="modal-footer">
				
				<button type="button" class="btn btn-danger" data-dismiss="modal">
					<i class="fa fa-times"></i> Cancelar
				</button>
				<button type="submit" class="btn btn-success">
					<i class="fa fa-envelope" ></i> Enviar
				</button>
								
				</div>
			
			</div>
		</div>
	</div>
</form>

<?php  include('scripts.php'); ?>
<script src="js/facturas.js"></script>



</body>
</html>
