<?php
// Technology Report

error_reporting(E_ALL);
ini_set("display_errors", 1);


$enlaces = array();
$doc = new DOMDocument;
$aux = new DOMDocument;

date_default_timezone_set('Europe/Madrid');
$hoy=date('Y\-m\-j');


for ($i = 1; $i <= 15; $i++) {

	$htmlpage="http://www.51voa.com/Technology_Report_".$i.".html";
	$page = iconv('UTF-8', 'UTF-8//IGNORE',file_get_contents($htmlpage));
	$page= str_replace("</a>","",$page);
	$page= str_replace("</li>","</a></li>",$page);
	//print_r($page);

	$doc->loadHTML($page);
	
	$lista = $doc->getElementById('list');
	$links = $lista->getElementsByTagName('a');

	
	
	foreach($links as $link) {
		if ($link->getAttribute('target') === '_blank') {
			$enlace=$link->ownerDocument->saveHTML($link);
			if (preg_match ( '/[0-1][0-9]-[0-1]?[0-9]-[0-3]?[0-9]/' ,$enlace,$matches)){
				$aux=$link->getAttribute('href');
				$enlaceValido="\"".$matches[0]."\",\"http://www.51voa.com".$aux."\"<br>";
				array_push($enlaces, $enlaceValido);
			}
		}
	
	}

} // Fin del bucle for

print_r($enlaces);


?>