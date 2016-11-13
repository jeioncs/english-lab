<?php
// NO FUNCIONA
error_reporting(E_ALL);
ini_set("display_errors", 1);

date_default_timezone_set('Europe/Madrid');
$hoy=date('Y\-m\-j');

$enlaces = array();
$doc = new DOMDocument;
$aux = new DOMDocument;



$page = iconv('UTF-8', 'UTF-8//IGNORE',file_get_contents("http://www.51voa.com/Education_Report_1.html"));
$doc->loadHTML($page);
$doc->formatOutput = true;
$doc->normalizeDocument();

$lista = $doc->getElementById('list');
$linksyfechas = $lista->getElementsByTagName('li');


foreach($linksyfechas as $linkyfecha) {
	//echo $linkyfecha->textContent."<br>";

    $aux=$linkyfecha->ownerDocument->saveHTML($linkyfecha);
    $links = $aux->getElementsByTagName('a');
    echo $aux;
 /*   
    foreach($links as $link) {
	if ($link->getAttribute('target') === '_blank') {
		$enlace=$link->ownerDocument->saveHTML($link);
	//	if (preg_match ( '/[0-1][0-9]-[0-1]?[0-9]-[0-3]?[0-9]/' ,$enlace,$matches)){
			$aux=$link->getAttribute('href');
			$enlaceValido="\"".$matches[0]."\",\"http://www.51voa.com".$aux."\"<br>";
			array_push($enlaces, $enlaceValido);
	//	}
	}
    }*/

}

print_r($enlaces);

?>