<?php
// Technology Report

//error_reporting(E_ALL);
//ini_set("display_errors", 1);


$enlaces = array();
$doc = new DOMDocument;
$aux = new DOMDocument;
$bd = new SQLite3('../0files/test.db');
$bd->exec('CREATE TABLE voanews (fecha STRING, enlace STRING)');

date_default_timezone_set('Europe/Madrid');
$hoy=date('Y\-m\-j');

$secciones=array("as_it_is","Agriculture_Report","Technology_Report","This_is_America","Science_in_the_News","Health_Report","Education_Report","Economics_Report","American_Mosaic","In_the_News","American_Stories","Words_And_Their_Stories","Everyday_Grammar","National_Parks","Explorations","The_Making_of_a_Nation","People_in_America");


foreach($secciones as $seccion) {
	

	for ($i = 1; $i <= 65; $i++) {
	
		$htmlpage="http://www.51voa.com/".$seccion."_".$i.".html";
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
					$enlaceValido="\"".$matches[0]."\",\"http://www.51voa.com".$aux."\"";
					$enlaceValido= str_replace("\"","'",$enlaceValido);
					$bd->exec("INSERT INTO voanews (fecha, enlace) VALUES (".$enlaceValido.")");
					//array_push($enlaces, $enlaceValido);
				}
			}
	
		}
	
	} // Fin del bucle for

}

/*
$results = $bd->query('SELECT * FROM voanews');

while ($row = $results->fetchArray()) {
	var_dump($row);
}
*/



?>