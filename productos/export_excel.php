<?php
	include("../lib/SimpleXLSXGen.php");
	
	$xlsx = new SimpleXLSXGen();
	
	// $books = [
	// ['ISBN', 'title', 'author', 'publisher', 'ctry' ],
	// [618260307, 'The Hobbit', 'J. R. R. Tolkien', 'Houghton Mifflin', 'USA'],
	// [908606664, 'Slinky Malinki', 'Lynley Dodd', 'Mallinson Rendel', 'NZ']
	// ];
	
	include('../conexi.php');
	$link = Conectarse();
	
	$arrResult = array();
	
	// if ($_GET["estatus_productos"]) {
	// $estatus_productos = $_GET["estatus_productos"];
	// } else {
	// $estatus_productos = 'ACTIVO';
	// }
	
	$consulta = "SELECT * FROM productos 
	LEFT JOIN departamentos USING (id_departamentos) 
	LEFT JOIN calidades USING (id_calidades) 
	
	ORDER BY descripcion_productos  
	";    
	if(isset($_GET["limit"])) {        
		$consulta.= " LIMIT {$_GET["limit"]}";
	}
	// if($_GET["existencia"] != '') {        
	// $consulta.= " AND existencia_productos < min_productos ";
	// }
	
	// $consulta.="
	// ORDER BY
	// {$_GET["sort"]} {$_GET["order"]}
	
	// ";
	$result = mysqli_query($link,$consulta);
	
	if(!$result){
		die("Error en $consulta" . mysqli_error($link) );
		}else{
		$num_rows = mysqli_num_rows($result);
		if($num_rows != 0){
			while($row = mysqli_fetch_assoc($result)){
				$arrResult[] = $row;        
				
			}
		}
		else{
			
		}
	}
	
	
	
	$export= [[
	"id_productos",
	"codigo_barras",
	"Departamento", 
	"Calidad", 
	"Descripcion",
	"Costo", 
	"Precio Publico", 
	"Mayoreo",
	"Distribuidor",
	"Fabrica",
	"Existencia"]
	];
	
	foreach($arrResult as $i=> $producto){
		// $semaforo = $producto["existencia"] < $producto["min_productos"] ? "bg-danger": "";
		// $badge =  $producto["existencia"] < $producto["min_productos"] ? "danger": "success";
		
		$export[] = [
		$producto["id_productos"], 
		$producto["codigo_productos"], 
		$producto["nombre_departamentos"], 
		$producto["calidad"], 
		iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $producto["descripcion_productos"]) , 
		$producto["costo_proveedor"], 
		$producto["precio_menudeo"], 
		$producto["precio_mayoreo"], 
		$producto["precio_dist"], 
		$producto["precio_fabrica"], 
		$producto["existencia_productos"]
		];
		
	}
	
	// print_r("<pre>");
	// print_r($export);
	// print_r("</pre>");
	
	$xlsx = SimpleXLSXGen::fromArray( $export );
	// $xlsx->saveAs('productos.xlsx');
	$xlsx->downloadAs('productos.xlsx');
	// $xlsx->download();
	
	// SimpleXLSXGen::download() or SimpleXSLSXGen::downloadAs('table.xlsx');
	// SimpleXSLSXGen::downloadAs('books.xlsx');
?>