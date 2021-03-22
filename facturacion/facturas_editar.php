<?php
	include("login/login_success.php");
	include_once("control/is_selected.php");
	include("conexi.php");
	$link = Conectarse();
	$menu_activo = "facturas";
	$id_emisores = $_SESSION["id_usuarios"];
	$regimen_emisores = $_SESSION["regimen_emisores"];
	$lugar_expedicion = $_SESSION["lugar_expedicion"];
	
	if(isset($_GET["id_facturas"])) $factura = copiarFactura($link, $_GET["id_facturas"]);
	
	function copiarFactura($link,$id_facturas){
		$factura = [];
		
		$consulta_factura = "SELECT * FROM facturas 
		LEFT JOIN clientes USING(id_clientes) 
		WHERE id_facturas = '$id_facturas'
		
		";
		
		$result = mysqli_query($link,$consulta_factura) ;
		
		if(!$result){
			$factura["error"] =  mysqli_error($link);
			
		}
		else{
			while($fila = mysqli_fetch_assoc($result)){
				$factura["datos"] = $fila;
				
			}
		}
		
		$consulta_detalle = "SELECT * FROM facturas_detalle 
		WHERE id_facturas = '$id_facturas'
		
		";
		
		$result = mysqli_query($link,$consulta_detalle) ;
		
		if(!$result){
			$factura["detalle"]["error"] =  mysqli_error($link);
			
		}
		else{
			while($fila = mysqli_fetch_assoc($result)){
				$factura["detalle"][] = $fila;
				
			}
		}
		
		
		
		return $factura;
		
	}
	
	function getProductos($link,$id_emisores ){
		$respuesta = "";
		$query = "SELECT * FROM productos 
		FULL JOIN productos_emisor USING(id_productos) 
		WHERE id_emisores = '$id_emisores'
		ORDER BY descripcion_productos
		";
		
		$result = mysqli_query($link,$query) ;
		
		if(!$result){
			return "<option value=''>Ocurrio un error".mysqli_error($link)."</option>"; 
		}
		else{
			while($fila = mysqli_fetch_assoc($result)){
				$respuesta.= "<option value='".$fila["id_productos"]."'>";
				$respuesta.= $fila["descripcion_productos"]."-".$fila["id_productos"]."</option>"; 
			}
		}
		return $respuesta; 
	}
	
	function getUnidades($link,$id_emisores ){
		$respuesta = "";
		$query = "SELECT * FROM unidades 
		FULL JOIN unidades_emisor USING(id_unidades) 
		WHERE id_emisores = '$id_emisores'
		ORDER BY nombre_unidades
		";
		
		$result = mysqli_query($link,$query) ;
		
		if(!$result){
			return "<option value=''>Ocurrio un error".mysqli_error($link)."</option>"; 
		}
		else{
			while($fila = mysqli_fetch_assoc($result)){
				$respuesta.= "<option value='".$fila["id_unidades"]."'>";
				$respuesta.= $fila["nombre_unidades"]."</option>"; 
			}
		}
		return $respuesta; 
	}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nueva Factura</title>
		<?php include("styles.php");?>
		<style>
			#form_conceptos div[class*="col-sm"]{
			padding-left: 5px !important;
			padding-right: 5px !important;
			} 
		</style>
	</head>
	<body>
		
		
		<div class="container-fluid">
			<?php include("menu.php");?>
		</div>
		
		
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12">
					<ul class="nav nav-pills nav-justified hidden-xs">
						<li class="active">
							<a class="" data-toggle="tab" id="tab_cliente"  href="#datos_cliente">1-Cliente</a>
						</li>
						<li class="">
							<a class=""  data-toggle="tab" id="tab_factura" href="#datos_factura">2-Factura</a>
						</li>
						<li class="">
							<a class="" data-toggle="tab" id="tab_conceptos" href="#datos_conceptos">3-Conceptos</a>
						</li>
					</ul>
					
					<div class="tab-content"> 
						<div class="tab-pane fade in active" id="datos_cliente">
							<form id="form_cliente">
								<div class="panel panel-primary">
									
									<div class="panel-body">
										<div class="row">
											<div class="col-sm-4 col-sm-offset-4">
												<div class="form-group hidden">
													<label for="">Id</label>
													<input type="text" readonly id="id_clientes" name="id_clientes" class="form-control" value="<?php echo $factura["datos"]["id_clientes"] ;?>">
												</div>
												<div class="form-group">
													<label for="">Alias o Seudónimo: </label>
													<input type="text" placeholder="(Opcional) Coloca un nombre fácil de recordar" name="alias_clientes" id="alias_clientes" class="form-control" value="<?php echo $factura["datos"]["alias_clientes"] ;?>">
												</div>
												<div class="form-group">
													<label for="">Razon Social: </label>
													<input type="text" placeholder="Escribe para Buscar" name="razon_social_clientes" id="razon_social_clientes" class="form-control" required value="<?php echo $factura["datos"]["razon_social_clientes"] ;?>">
												</div>
												<div class="form-group">
													<label for="">RFC: </label>
													<input type="text" name="rfc_clientes" id="rfc_clientes" class="form-control" required value="<?php echo $factura["datos"]["rfc_clientes"] ;?>">
												</div>
												<div class="form-group hidden">
													<label for="">Numero de Cuenta: </label>
													<input type="number" placeholder="Ultimos 4 dígitos de la cuenta" name="num_cuenta" id="num_cuenta" class="form-control" >
												</div>
												<div class="form-group">
													<div class="dasd"><label><input type="checkbox" id="enviar_correo" checked> Correo: </label></div>
													<input type="text" name="correo_clientes" id="correo_clientes" class="form-control minus" required value="<?php echo $factura["datos"]["correo_clientes"] ;?>">
												</div>
												
												<input type="number" name="id_emisores" id="" class="hidden" value="<?php echo $_SESSION["id_usuarios"]?>">
												
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<button type="submit"  class="btn btn-success btn-lg pull-right next">
													Siguiente <i class="fa fa-arrow-right"></i>
												</button>
											</div>
										</div>
										
									</div>
								</div>
							</form>
						</div>
						
						
						<div class="tab-pane fade" id="datos_factura">
							<form id="form_factura">
								<div class="panel panel-primary">
									<div class="panel-heading">
										<h4>
											2-Datos de la Factura
										</h4>
									</div>
									<div class="panel-body">
										<div class="row">
											<div class="col-sm-10 col-sm-offset-1">
												<div class="form-group col-sm-6">
													<label class="control-label" for="forma_pago">Serie:</label>
													<input type="text" name="serie" id="serie" class="form-control" value="" >
												</div>
												<div class="form-group col-sm-6">
													<label class="control-label" for="forma_pago">Folio:</label>
													<input type="text" name="folio" id="folio" class="form-control" value="" >
												</div>
												<div class="form-group col-sm-6">
													<label for="">Metodo de pago</label>
													<select id="metodo_pago" name="metodo_pago" class="form-control" >
														<option value="">Seleccione...</option>
														<option <?php echo $factura["datos"]["metodo_pago"] == "PUE" ? "selected": "";?> value="PUE">Pago en una sola exhibición</option>
														<option <?php echo $factura["datos"]["metodo_pago"] == "PPD" ? "selected": "";?> value="PPD" >Pago en parcialidades o diferido</option>
														<option <?php echo $factura["datos"]["metodo_pago"] == "PIP" ? "selected": "";?> value="PIP" >Pago Inicial y parcialidades</option>
													</select>
												</div>
												
												<div class="form-group col-sm-6">
													<label class="control-label" for="forma_pago">Forma de Pago:</label>
													<select id="forma_pago" name="forma_pago" class="form-control" >
														<option  value="">Seleccione...</option>
														<option <?php echo $factura["datos"]["forma_pago"] == "01" ? "selected": "";?>  value="01" >01 Efectivo</option>
														<option  <?php echo $factura["datos"]["forma_pago"] == "02" ? "selected": "";?> value="02">02 Cheque nominativo</option>
														<option  <?php echo $factura["datos"]["forma_pago"] == "03" ? "selected": "";?> value="03" >03 Transferencia electrónica de fondos</option>
														<option  <?php echo $factura["datos"]["forma_pago"] == "04" ? "selected": "";?> value="04">04 Tarjeta de crédito</option>
														<option  <?php echo $factura["datos"]["forma_pago"] == "28" ? "selected": "";?> value="28" >28 Tarjeta de débito</option>
														<option  <?php echo $factura["datos"]["forma_pago"] == "29" ? "selected": "";?> value="29" >29 Tarjeta de servicios</option>
														<option  <?php echo $factura["datos"]["forma_pago"] == "99" ? "selected": "";?> value="99" >99 Por definir</option>
													</select>
												</div>
												<div class="form-group col-sm-6">
													<label for="">Lugar de Expedición</label>
													<input type="text" name="lugar_expedicion" id="lugar_expedicion" class="form-control" value="<?php echo $lugar_expedicion;?>" required>
												</div>
												<div class="form-group col-sm-6">
													<label for="">Tipo de Comprobante</label>
													<select id="tipocomprobante" name="tipocomprobante" class="form-control" >
														<option value="">Seleccione...</option>
														<option value="E">E Egreso</option>
														<option selected value="I">I Ingreso</option>
														<option value="N">N Nómina</option>
														<option value="P">P Pago</option>
														<option value="T">T Traslado</option>
													</select>
												</div>
												<div class="form-group col-sm-6">
													<label for="">Uso CFDI</label>
													<select id="uso_cfdi" name="uso_cfdi" class="form-control" >
														<option value="G01">G01 Adquisición de mercancias</option>
														<option value="G02">G02 Devoluciones, descuentos o bonificaciones</option>
														<option selected value="G03">G03 Gastos en general</option>
														<option value="I01">I01 Construcciones</option>
														<option value="I02">I02 Mobilario y equipo de oficina por inversiones</option>
														<option value="I03">I03 Equipo de transporte</option>
														<option value="I04">I04 Equipo de computo y accesorios</option>
														<option value="I05">I05 Dados, troqueles, moldes, matrices y herramental</option>
														<option value="I06">I06 Comunicaciones telefónicas</option>
														<option value="I07">I07 Comunicaciones satelitales</option>
														<option value="I08">I08 Otra maquinaria y equipo</option>
														<option value="D01">D01 Honorarios médicos, dentales y gastos hospitalarios.</option>
														<option value="D02">D02 Gastos médicos por incapacidad o discapacidad</option>
														<option value="D03.">D03 Gastos funerales.</option>
														<option value="D04.">D04 Donativos.</option>
														<option value="D05">D05 Intereses reales efectivamente pagados por créditos hipotecarios (casa habitación).</option>
														<option value="D06">D06 Aportaciones voluntarias al SAR.</option>
														<option value="D07">D07 Primas por seguros de gastos médicos.</option>
														<option value="D08">D08 Gastos de transportación escolar obligatoria.</option>
														<option value="D09">D09 Depósitos en cuentas para el ahorro, primas que tengan como base planes de pensiones.</option>
														<option value="D10">D10 Pagos por servicios educativos (colegiaturas)</option>
														<option value="P01">P01 Por definir</option>
													</select>
												</div>
												
												<div class="form-group col-sm-6">
													<label class="control-label" for="regimen_emisores">
														Régimen fiscal
														<span class="requerido">*</span>:
													</label>
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
												
												<DIV class="row">
													<div class="col-sm-6 ">
														<label >
															<input type="checkbox" name="activa_addenda" id="activa_addenda">
															Addenda:
														</label>
														<textarea rows="4" cols="8" class="form-control" name="addenda" id="addenda"><?php echo $factura["datos"]["addenda"]?></textarea>
													</div>
													<div class="col-sm-6 ">
														<label >Observaciones: </label>
														<textarea rows="4" cols="8" class="form-control" name="observaciones" id="observaciones"><?php echo $factura["datos"]["observaciones"]?>
														</textarea>
													</div>
												</div>
												<div class="col-sm-6 checkbox">
													<label >
														<input type="checkbox" name="modo_pruebas" value="SI"> MODO PRUEBAS
													</label>
												</div>
												<div class="row">	
													<div class="col-sm-12">	
														
														<a  type="button"  class="btn btn-success btn-lg pull-left anterior">
															Anterior <i class="fa fa-arrow-left"></i>
														</a>
														<button   type="submit"  class="btn btn-success btn-lg pull-right next">
															Siguiente <i class="fa fa-arrow-right"></i>
														</button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div> 
						
						
						<div class="tab-pane fade" id="datos_conceptos">
							<form id="form_conceptos">
								<div class="panel panel-primary ">
									<div class="panel-heading">
										<h4>
											2-Conceptos
											<button type="button" class="btn btn-success pull-right" id="agregar_concepto">
												<i class="fa fa-plus"></i> Agregar Concepto
											</button>
										</h4>
									</div>
									<div class="panel-body " >
										<div class="row">
											<div class="col-sm-1" >
												<label>CANTIDAD</label>
											</div>
											<div class="col-sm-1" >
												<label>UNIDAD</label>
											</div>
											<div class="col-sm-2" >
												<label>CLAVE</label>
											</div>
											<div class="col-sm-3" >
												<label>DESCRIPCIÓN</label>
											</div>
											<div class="col-sm-1" >
												<label>PRECIO UNITARIO C/IVA</label>
											</div>
											<div class="col-sm-1" >
												<label>IVA UNITARIO</label>
											</div>
											<div class="col-sm-1" >
												<label>PRECIO UNITARIO S/IVA</label>
											</div>
											<div class="col-sm-1" >
												<label>IMPORTE</label>
											</div>
											
										</div>
										
										<div id="div_conceptos">
											<?php foreach($factura["detalle"] as $i => $concepto){?>
												<div class="fila_concepto">
													<div class="row ">
														<div class="col-sm-1">
															<input required type="number" min="0" step=".01"  name="cantidad[]" class="form-control cantidad conceptos" value="<?php echo $concepto["cantidad"]?>">
														</div>	
														<div class="col-sm-1">
															<select required  name="clave_unidad[] " class="form-control clave_unidad conceptos">
																<option value="">Elige...</option>
																<?php echo  getUnidades($link,$id_emisores );?>
															</select>
															<input type="text" class="nombre_unidades hidden" name="nombre_unidades[]" >
														</div>	
														<div class="col-sm-2">
															<select required  name="clave_producto[]" class="form-control  conceptos">
																<option value="">Elige...</option>
																<?php echo  getProductos($link,$id_emisores );?>
															</select>
														</div>
														<div class="form-group col-sm-3">
															<textarea required cols="6"  rows="2" value="" placeholder=""  name="descripcion[]" class="form-control conceptos"><?php echo $concepto["descripcion"]?></textarea>
														</div>
														<div class="col-sm-1">
															<input  type="number" min="0" step=".01"  name="" class="form-control precio_unitario conceptos">
														</div>
														<div class="col-sm-1">
															<input  required type="number" min="0" step=".01"  class="form-control iva_unitario conceptos">
														</div>
														<div class="col-sm-1">
															<input  value="<?php echo $concepto["precio"]?>" type="number" min="0" step=".01" name="precio_unitario[]" class="form-control conceptos precio_sin_iva">
														</div>
														<div class=" col-sm-1">
															<input required  value="<?php echo $concepto["importe"]?>" type="number" min="0" step=".01"  name="importe[]" class="form-control importe conceptos">
														</div>
														<div class="col-sm-1 hidden">
															<input  type="number" min="0" step="any"  name="iva[]" class="form-control iva conceptos">
														</div>
														<div class="col-sm-1">
															<button type="button" class="btn btn-danger btn_borrar" title="Eliminar">
																<i class="fa fa-times"></i>
															</button>
														</div> 
													</div>
													<div class="row  ">
														<div class="col-sm-2 col-sm-offset-2 ">
															<h3>
																Impuestos
																<button type="button" class="btn btn-success agregar_impuesto" title="Agregar Impuesto">
																	<i class="fa fa-plus"></i>
																</button>
															</h3>
														</div>
														<div class="impuestos col-sm-8">
															<div class="fila_impuesto row">
																<div class="col-sm-2 ">
																	<label>Tipo Impuesto</label>
																	<select name="tipo_impuesto[0][]" class="form-control tipo_impuesto">
																		<option  value="Traslado"> Traslado</option>
																		<option  value="Retención"> Retención</option>
																	</select>
																</div>	
																<div class="col-sm-2 ">
																	<label>Impuesto</label>
																	<select required  name="impuesto[0][]" class="form-control ">
																		<option value="">Elige...</option>
																		<option value="001">ISR</option>
																		<option value="002" selected>IVA</option>
																	</select>
																	
																</div>	
																<div class="form-group col-sm-2">
																	<label>Base:</label>
																	<input  name="base[0][]" value="0" type="number" min="0" step="any"  class="form-control base">
																</div>
																<div class="form-group col-sm-2 ">
																	<label>Tasa:</label>
																	<!-- <input  value="0" type="number" min="0" step="any" name="tasa[]" class="form-control tasa"> !-->
																	<select required  name="tasa[0][]" class="form-control tasa">
																		<option value="">Elige...</option>
																		<option  value=".000000">0%</option>
																		<option selected value=".160000">16%</option>
																		<option value=".106666" >10.66%</option>
																		<option value=".100000" >10%</option>
																	</select>
																</div>
																<div class="form-group col-sm-2 hidden">
																	<label>Tipo Factor:</label>
																	<select name="tipo_factor[0][]" class="form-control tipo_factor">
																		<option selected value="Tasa"> Tasa</option>
																		<option  value="Cuota"> Cuota</option>
																	</select>
																</div>
																<div class="form-group col-sm-2">
																	<label>Importe:</label>
																	<input name="impuesto_importe[0][]" value="0" type="number" min="0" step="any"  class="form-control impuesto_importe">
																	
																</div>
																<div class="form-group col-sm-1">
																	<label>Eliminar:</label>
																	<button type="button" name="eliminar[0][]" class="btn btn-danger borrar_impuesto pull-right" title="Eliminar">
																		<i class="fa fa-times"></i>
																	</button>
																</div>
																
															</div>
														</div>
													</div>
													<hr>
												</div>
												<?php
												}
											?>
										</div>
										
										
										
										<div class="row">
											<div class="col-sm-3 col-sm-offset-6 text-right">
												<label>SUBTOTAL:</label>
											</div>
											<div class="col-sm-3">
												<input required  type="number" step="any" class="form-control" name="subtotal" id="subtotal">
											</div>
										</div>
										<div class="row">
											<div class="col-sm-3 col-sm-offset-6 text-right">
												<label>DESCUENTO:</label>
											</div>
											<div class="col-sm-3">
												<input  type="number" step=".01" class="form-control" name="descuento_total" id="descuento">
											</div>
										</div>
										<div class="row">
											<div class="col-sm-3 col-sm-offset-6 text-right">
												<label>TRASLADADOS:</label> 
											</div>
											<div class="col-sm-3">
												<input  type="number" step="any" class="form-control" name="total_traslados" id="total_traslados">
											</div>
										</div>
										<div class="row">
											<div class="col-sm-3 col-sm-offset-6 text-right">
												<label>RETENIDOS:</label>
											</div>
											<div class="col-sm-3">
												<input required  type="number" step="any" class="form-control" name="total_retenciones" id="total_retenciones">
											</div>
										</div>
										<div class="row">
											<div class="col-sm-3 col-sm-offset-6 text-right">
												<label>TOTAL:</label>
											</div>
											<div class="col-sm-3">
												<input required  type="number"  step="any" class="form-control" name="total_pagos" id="total">
											</div>
										</div>
										<hr> 
										<div id="mensaje_error" class="alert alert-danger hidden">
											
										</div>
										<div id="mensaje_timbrado" class="alert alert-success hidden">
											Facturando <i class="fa fa-spinner fa-spin"></i>
										</div>
										<div id="mensaje_pdf" class="alert alert-success hidden">
											Generando PDF <i class="fa fa-spinner fa-spin"></i>
										</div>
										<div id="mensaje_correo" class="alert alert-success hidden">
											Enviando Correo <i class="fa fa-spinner fa-spin"></i>
										</div>
										<pre id="debug" >
										</pre>
										<a   type="button"  class="btn btn-success btn-lg pull-left anterior">
											Anterior <i class="fa fa-arrow-left"></i>
										</a>
										<button  type="submit" id="btn_facturar"  class="btn btn-success btn-lg pull-right">
											Facturar <i class="fa fa-arrow-right"></i>
										</button>
										<button  type="button" id="btn_guardar"  class="btn btn-warning btn-lg pull-right ">
											Guardar Borrador <i class="fa fa-save"></i>
										</button>
										
									</div>
								</div>
							</form>
						</div><!--/tab-pane -->
					</div><!--/tab-content -->
				</div><!--/col-sm-12-->
			</div><!--/row-->
			<a href="https://m.me/GlifoMedia">Envíanos un mensaje de Facebook</a>
		</div><!--/container-->
		
		<hr>
		
		<pre>
			<?php print_r($factura);?>
		</pre>
		<?php echo date('Y-m-d\TH:i:s', time() - 120);?>
		<?php include("scripts.php");?>
		<script src="js/facturas_nueva.js"></script>
		
		
	</body>
</html>