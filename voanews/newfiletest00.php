<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

date_default_timezone_set('Europe/Madrid');
$hoy=date('Y\-m\-j');




$page = iconv('UTF-8', 'UTF-8//IGNORE',file_get_contents("http://www.51voa.com/"));
$enlaces = array();
$doc = new DOMDocument;
$doc->loadHTML($page);

$lista = $doc->getElementById('list');
$links = $lista->getElementsByTagName('a');

$aux = new DOMDocument;

foreach($links as $link) {
	if ($link->getAttribute('target') === '_blank') {
		$enlace=$link->ownerDocument->saveHTML($link);
		if (preg_match ( '/20[0-1][0-9]-[0-1]?[0-9]-[0-3]?[0-9]/' ,$enlace,$matches)){
			$aux=$link->getAttribute('href');
			$enlaceValido="\"".$matches[0]."\",\"http://www.51voa.com".$aux."\"<br>";
			array_push($enlaces, $enlaceValido);
		}
	}

}

print_r($enlaces);

?>