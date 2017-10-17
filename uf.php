#!/usr/bin/php -q
<?php
// Sistema de Aprovisionamiento Web
//
// Script que obtiene el valor de la UF desde el SII
//
// 2014-03-12 jzorrilla@x-red.com - Creo la version inicial basado en un scritp de Henry Lopez


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

$anio = date("Y");

$ch = curl_init(); 

curl_setopt($ch,CURLOPT_URL,$URL); 

curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 

$output = curl_exec($ch); 

curl_close($ch); 

$temp_array = array(); 

//$search_for = '<tr\s[^>]*class="texto1">(.*)<\/tr>'; 
$search_for = "<td style='text-align:right;'>(.*)<\/td>";
//$search_td = array('<tr align="center" bgcolor="#F7F7F7" class="texto1">','</tr>'); 
$search_td = array("<td style='text-align:right;'>","</td>"); 

if(preg_match_all("/$search_for/siU",$output,$matches)) { 
   foreach($matches[0] as $match) { 
      $to_push = str_replace($search_td,'',$match);
      $to_push = trim($to_push); 
	  $to_push = str_replace(".","",$to_push);
	  $to_push = str_replace(",",".",$to_push);
      array_push($temp_array,$to_push); 
   } 
} 
$mes = array("01","02","03","04","05","06","07","08","09","10","11","12");
$dia = array("01","02","03","04","05","06","07","08","09","10",
			 "11","12","13","14","15","16","17","18","19","20",
			 "21","22","23","24","25","26","27","28","29","30","31");
$m = 0;
$d = 0;
$i = 0;
while ($d <= 30) {
	if($temp_array[$i] <> "&nbsp;") {
		$fecha = $anio . "-" . $mes[$m] . "-" . $dia[$d];
		$sql = "insert ignore into uf (uf_fecha,uf_valor,uf_fechaact) values ('" . $fecha . "'," . $temp_array[$i] .",now())\n";
		 echo $sql ."\n";
		$con=mysql_query($sql);
	}
	$m = $m + 1;
	if ($m == 12){
		$m = 0;
		$d = $d + 1;
	}

	$i = $i + 1;
}
?>