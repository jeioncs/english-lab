<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

$i=0;
$doc = new DOMDocument;

date_default_timezone_set('Europe/Madrid');
//$hoy=date('Y\-m\-j');
$hoy="2016-12-07";

$paginas_revisar = array();
$paginas_revisar = array("http://www.51voa.com/VOA_Special_English/","http://www.51voa.com/VOA_Standard_English/");
$enlaces = array();

foreach ($paginas_revisar as &$pagina) {

	$page = iconv('UTF-8', 'UTF-8//IGNORE',file_get_contents($pagina));


	$doc->loadHTML($page);

	$lista = $doc->getElementById('list');
	$links = $lista->getElementsByTagName('a');

	$aux = new DOMDocument;

	foreach($links as $link) {
		if ($link->getAttribute('target') === '_blank') {
			$enlace=$link->ownerDocument->saveHTML($link);
			if (preg_match ( '/'.str_replace("-0","-",$hoy).'/' ,$enlace)){
				$aux=$link->getAttribute('href');
				$enlaceValido="http://www.51voa.com".$aux."";
				array_push($enlaces, $enlaceValido);
			}
		}

	}

} // Fin del primer foreach


$base_pages=$enlaces;

print_r($base_pages);

$fp0 = fopen('../0files/execBash-'.$hoy.'.sh', 'a+');
fwrite($fp0,"\ncp ../voanews/*.jpg ./ \n");
fwrite($fp0,"\ncurl -o voafile0.mp3 http://gandalf.ddo.jp/mp3/".str_replace("-","",substr($hoy,2)).".mp3\n");
fwrite($fp0,"\nmv voafile0.mp3 aux_voafile0.mp3\n");
fwrite($fp0,"\navconv -y -i 'aux_voafile0.mp3' -acodec libmp3lame -b:a 64k -ac 1 -ar 44100 'voafile0.mp3'\n");
fclose($fp0);

$manythings_page="http://gandalf.ddo.jp/html/".str_replace("-","",substr($hoy,2)).".html";
$manythings_page=iconv('UTF-8', 'UTF-8//IGNORE',file_get_contents($manythings_page));
$doc1 = new DOMDocument;
$doc1->loadHTML($manythings_page);
$contents = $doc1->getElementsByTagName('td');
foreach($contents as $content) {
	$contenido=$content->textContent;
}

$fp1 = fopen('../0files/texto.txt', 'a+');
fwrite($fp1,"% Voa News ".$hoy."\n");
fwrite($fp1,"% Compilación JEI\n");
fwrite($fp1,"\n\n\n");
fwrite($fp1,"Voa News\n");
fwrite($fp1,"============================\n");
fwrite($fp1,"\n\n\n");
fwrite($fp1,$contenido);
fwrite($fp1,"\n\n\n");
fwrite($fp1,"Voa Special News\n");
fwrite($fp1,"============================\n");

fclose($fp1);

print_r($contenido);


foreach($base_pages as &$base_page) {

	if ( preg_match ( '/VOA_Standard_English/' ,$base_page) || preg_match ( '/VOA_Special_English/' ,$base_page)){

		$page = iconv('UTF-8', 'UTF-8//IGNORE',file_get_contents($base_page));

		$doc = new DOMDocument;
		$doc->loadHTML($page);
		$doc->formatOutput = true;
		$doc->normalizeDocument();

		$title = $doc->getElementById('title');
		$temp_dom0 = new DOMDocument();
		$temp_dom0->appendChild($temp_dom0->importNode($title,TRUE)) ;
		$titulo= str_replace("","",$temp_dom0->saveHTML());

		$playbar = $doc->getElementById('playbar')->textContent;
		$playbar= str_replace("\");","",$playbar);
		$playbar= str_replace("Player(\"","http://downdb.51voa.com",$playbar);

		$content = $doc->getElementById('content');
		$temp_dom1 = new DOMDocument();
		$temp_dom1->appendChild($temp_dom1->importNode($content,TRUE)) ;
		$texto= str_replace("","",$temp_dom1->saveHTML());

		print_r ($playbar);
		echo trim($titulo."<br>\n");
		print_r (strip_tags($texto));


		// Escribimos un fichero con comandos bash para construir el archivo de audio

		$i++;
		$fp0 = fopen('../0files/execBash-'.$hoy.'.sh', 'a+');
		$playbar=trim($playbar);
		fwrite($fp0,"\ncurl -o voafile".$i.".mp3 ".$playbar."\n");
		fwrite($fp0,"\nmv voafile".$i.".mp3 aux_voafile".$i.".mp3\n");
		fwrite($fp0,"\navconv -y -i 'aux_voafile".$i.".mp3' -acodec libmp3lame -b:a 64k -ac 1 -ar 44100 'voafile".$i.".mp3'\n");
		fclose($fp0);


		$fp1 = fopen('../0files/texto.txt', 'a+');
		$titulo=preg_replace('/&#[0-9][0-9][0-9][0-9][0-9];/', '', $titulo); // Quita los caracteres chinos
		fwrite($fp1,"\n".strip_tags($titulo)."");
		fwrite($fp1,"----------------------------------------------------------------------------\n");
		$texto= preg_replace('/&#[0-9][0-9][0-9][0-9][0-9];/', '', $texto); // Quita los caracteres chinos
		$texto= str_replace("</p>","\n",$texto);
		$texto= str_replace("</span>","\r\n\r\n",$texto);
		$texto= str_replace("</div>","\r\n\r\n",$texto);
		$texto= str_replace("<strong>","**",$texto);
		$texto= str_replace("</strong>","**",$texto);
		$texto= str_replace("<h2>","**",$texto);
		$texto= str_replace("</h2>","**\r\n",$texto);
		$texto= str_replace("** **","",$texto);
		$texto= str_replace("****","**",$texto);
		$texto= strip_tags($texto);
		fwrite($fp1,"\n".$texto."");
		fclose($fp1);


	} // Fin del if preg_match

}

$fp0 = fopen('../0files/execBash-'.$hoy.'.sh', 'a+');
fwrite($fp0,"\nconvert COVER_mp3.jpg  -font Arial -pointsize 52 -draw \"gravity north fill black  text 0,230 '".$hoy."' fill yellow  text 1,234 '".$hoy."' \" cover_mp3.jpg\n");
fwrite($fp0,"\nmp3wrap aux.mp3 voafile{0..".$i."}.mp3\n");
fwrite($fp0,"\nlame -m m -b 64 aux_MP3WRAP.mp3 --id3v2-only --ignore-tag-errors --ta 'Internet' --tt 'VoaNews ".$hoy."' --ti 'cover_mp3.jpg' VoaNews-".$hoy.".mp3\n");
fwrite($fp0,"\nrm aux* voafile* cover_mp3.jpg\n");
fclose($fp0);

$fp2 = fopen('../0files/execBash-'.$hoy.'.sh', 'a+');
fwrite($fp2,"\nconvert COVER_book.jpg  -font Arial -pointsize 52 -draw \"gravity north fill black  text 10,270 '".$hoy."' fill yellow  text 12,274 '".$hoy."' \" cover_book.jpg\n");
fwrite($fp2,"\npandoc texto.txt -o aux_texto.md --parse-raw\n");
fwrite($fp2,"\npandoc texto.txt -o  VoaNews-".$hoy.".epub --epub-cover-image=cover_book.jpg  --toc\n");
fwrite($fp2,"\nrm texto.txt  aux_texto.md *.jpg \n");
fclose($fp2);

?>
