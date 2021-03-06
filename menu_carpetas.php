<nav class="navbar navbar-default ">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Cambiar Navegación</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="../index.php">
				<img src="../img/logo_small.jpg" class="img-responsive" width="45px">
			</a>
		</div>
		
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<?php
					if ($_COOKIE['permiso_usuarios'] == 'mostrador' || $_COOKIE['permiso_usuarios'] == 'administrador') {
						
					?>
					<li class="<?php echo $menu_activo == "principal" ? "active" : ''; ?>">
						<a href="../index.php">
							<i class="fas fa-dollar-sign"></i> Ventas
						</a>
					</li>					
					<?php
					}
				?>
				
				<?php
					if ($_COOKIE['nombre_usuarios'] == 'VANESSA') {
					?>
					<li class="dropdown <?php echo $menu_activo == "reportes" ? "active" : ''; ?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="fas fa-chart-bar"></i> Reportes <strong class="caret"></strong>
						</a>
						<ul class="dropdown-menu">
							<li>
								<a href="../inventarios/movimientos.php"><i class="fas fa-chart-bar"></i> Movimientos</a>
							</li>
						</ul>
					</li>
					<?php
					}
				?>
				
				<?php
					if ($_COOKIE["permiso_usuarios"] == "administrador") { ?>
					<li class=" <?php echo $menu_activo == "compras" ? "active" : ''; ?>">
						<a href="../compras/compras_lista.php">
							<i class="fas fa-shopping-cart"></i> Compras
						</a>
					</li>
					<li class=" <?php echo $menu_activo == "proveedores" ? "active" : ''; ?>">
						<a href="../proveedores/index.php">
							<i class="fas fa-truck"></i> Proveedores
						</a>
					</li>
					<li class="dropdown <?php echo $menu_activo == "reportes" ? "active" : ''; ?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="fas fa-chart-bar"></i> Reportes <strong class="caret"></strong>
						</a>
						<ul class="dropdown-menu">
							<li>
								<a href="../reportes"> Ventas Por Día</a>
							</li>
							<li>
								<a href="../reportes/ventas_vendedor.php">Ventas por Vendedor</a>
							</li>
							<li>
								<a href="../inventarios/movimientos.php"> Movimientos</a>
							</li>	
							
						</ul>
					</li>
					
					<li class=" <?php echo $menu_activo == "producto" ? "active" : ''; ?>">
						<a href="../productos">
							<i class="fa fa-list"></i> Productos
						</a>
					</li>
					
					<li class="dropdown <?php echo $menu_activo == "catalogos" ? "active" : ''; ?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="fas fa-file-alt"></i> Catálogos <strong class="caret"></strong>
						</a>
						<ul class="dropdown-menu">
							<li>
								<a href="../catalogos/departamentos.php"><i class="fas fa-file-alt"></i> Departamentos</a>
							</li>
							<li>
								<a href="../catalogos/subdepartamentos.php"><i class="fas fa-file-alt"></i> Subdepartamentos</a>
							</li>
							<li>
								<a href="../proveedores/index.php">
									<i class="fas fa-truck"></i> Proveedores
								</a>
							</li>
							
							<li>
								<a href="../calidades/index.php"><i class="fas fa-file-alt"></i> Calidades</a>
							</li>
						</ul>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="../facturacion/facturas.php">
							<i class="fas fa-qrcode"></i> Facturación
							<span class="badge badge-secondary"></span>
							
						</a>
					</li>
					<?php
					}
					if ($_COOKIE["permiso_usuarios"] == "caja" || $_COOKIE['permiso_usuarios'] == "administrador" ) {
					?>
					<li class="<?php echo $menu_activo == "resumen" ? "active" : ''; ?>">
						<a href="../corte/resumen.php">
							<i class="fas fa-cash-register"></i> Corte
						</a>
					</li>
					<li class="<?php echo $menu_activo == "cobrar" ? "active" : ''; ?>">
						<a href="../cobrar/cobrar.php">
							<i class="fas fa-dollar-sign"></i> Cobrar
						</a>
					</li>
					<?php
					}
				?>
				
				
			</ul>
			
			<ul class="nav navbar-nav navbar-right">
				<?php
					if ($_COOKIE["permiso_usuarios"] == "administrador") {
					?>
					<li>
						<a href="../usuarios.php"><i class="fa fa-user-plus "></i> Usuarios</a>
					</li>
					<?php
					}
				?>
				<li hidden class="hidden">
					<a href="#">
						<i class="fas fa-clock"></i> Turno:
						<span id="turno_span"></span>
						<input type="hidden" id="menu_id_turnos">
					</a>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-user"></i>
						<span id="menu_nombre_usuario">
							<?php echo isset($_COOKIE["nombre_usuarios"]) ? $_COOKIE["nombre_usuarios"] : "" ?>
						</span>
						<strong class="caret"></strong>
						<input type="hidden" id="id_usuarios" value="<?php echo isset($_COOKIE["id_usuarios"]) ? $_COOKIE["id_usuarios"] : ""; ?>">
					</a>
					<ul class="dropdown-menu">
						<li>
							<a href="../login/logout.php"><i class="fas fa-sign-out-alt"></i> Salir</a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</nav>