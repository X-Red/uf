#!/usr/bin/php -q
<?php
// Sistema de Aprovisionamiento Web
//
// Script que obtiene el valor de la UF desde el SII
//
// 2014-03-12 jzorrilla@x-red.com - Creo la version inicial basado en un scritp de Henry Lopez
// 2017-10-23 jzorrilla@x-red.com - Actualizo el script para que utilice webservices desde mindicador.cl


// Valida que exista archivo de configuracion
if (file_exists(dirname(__FILE__) . "/config.php")) {
	include(dirname(__FILE__) . "/config.php");
} else {
	echo "Error: Archivo de configuracion " . dirname(__FILE__) . "/config.php no existe\n";
	exit;
}

// Si existe archivo de configuracion local lo incluye, este archivo esta en .gitignore
// Este archivo sobre escribe las variables de config.php
if (file_exists(dirname(__FILE__) . "/config_local.php")) {
        include(dirname(__FILE__) . "/config_local.php");
} 

// Define el timezone
date_default_timezone_set('America/Santiago');

// Conexion a la base de datos
mysql_connect($DB_SERVIDOR,$DB_USUARIO,$DB_CLAVE) or die("Imposible conectarse al servidor.");
mysql_select_db($DB_BASE) or die("Imposible abrir Base de datos");

$fecha = date_create(date("Y-m-d"));

$fin = 0;

while (!$fin) {
	//echo date_format($fecha, 'd-m-Y') . "\n";
	//echo date_format($fecha,'z') . "\n";

	$jsonsource = $URL . date_format($fecha,'d-m-Y');

	$json = json_decode(file_get_contents($jsonsource));

	if (sizeof($json->serie)!=0) {
			$sql = "insert ignore into uf (uf_fecha,uf_valor,uf_fechaact) values ('" . 
				date_format($fecha,'Y-m-d') . "'," . $json->serie[0]->valor .",now())\n";
			echo $sql;
			$con=mysql_query($sql);
	}
	else
		$fin =1;

	date_add($fecha, date_interval_create_from_date_string('1 days'));

}
?>