<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

//mp3cut -o 01_ESL289.mp3 -t 1:24-3:18 ESLPod289.mp3
   
//$page = file_get_contents('https://www.eslpod.com/website/show_podcast.php?issue_id=19021161');

//Creates a blank array.
$id_podcast = array();

//Creates an array with elements.
$id_podcast = array("4245863","4255806","4265749","4275692","4315464","4325407","4335350","4345293","4355236","4365179","4385065","4395008","4414894","4424837","4474552","4484495","4494438","4504381","4514324","4524267","4544153","4554096","4583925","4593868","4603811","4613754","4653526","4663469","4673412","4683355","4693298","4703241","4733070","4743013","4752956","4762899","4772842","4782785","4792728","4802671","4812614","4822557","4832500","4842443","4941873","4951816","4961759","4971702","4981645","4991588","5001531","5011474","5061189","5071132","5081075","5091018","5120847","5130790","5140733","5150676","5170562","5180505","5200391","5210334","5220277","5230220","5250106","5260049","5289878","5299821","5329650","5339593","5349536","5359479","5389308","5399251","5409194","5419137","5468852","5478795","5488738","5498681","5508624","5518567","5528510","5538453","5578225","5588168","5598111","5608054","5617997","5627940","5647826","5657769","5697541","5707484","5717427","5727370","5737313","5747256");


$base_page="https://www.eslpod.com/website/show_podcast.php?issue_id=";


foreach ($id_podcast as &$valor_id) {


$page = iconv('UTF-8', 'UTF-8//IGNORE',file_get_contents($base_page."".$valor_id));

$doc = new DOMDocument;
$doc->loadHTML($page);
$doc->formatOutput = true;
$doc->normalizeDocument();


$divs = $doc->getElementsByTagName('table');
$titles = $doc->getElementsByTagName('b');



foreach($titles as $title) {
    if ($title->getAttribute('class') === 'pod_title') {
        $title_pod=$title->nodeValue;    
       } 
}

$title_pod=str_replace("ESL Podcast ","",$title_pod);


$temp_dom0 = new DOMDocument();
$temp_dom1 = new DOMDocument();

$i=0;

foreach($divs as $div) {

    if ($div->getAttribute('class') === 'podcast_table_home') {
	if ($i == 0) $temp_dom0->appendChild($temp_dom0->importNode($div,TRUE)) ;
	if ($i == 1) $temp_dom1->appendChild($temp_dom1->importNode($div,TRUE)) ;
	$i=$i+1; 
       } 

}


$texto= str_replace("<b>","**",$temp_dom1->saveHTML());
$texto= str_replace("</b>","**",$texto);
$texto = preg_replace('/\s+/', ' ', $texto);
$texto = str_replace('  ', ' ', $texto);
$texto= str_replace("<br>","<br>\n",$texto);






$dom = new DOMDocument;
$dom->loadHTML($page);
foreach ($dom->getElementsByTagName('a') as $node)
{
	if (preg_match("/mp3/",$node->getAttribute('href'))== 1){
	  $link=$node->getAttribute("href");
	}
}




$file= explode("/", $link);

$time = $temp_dom0->textContent;
$time = preg_replace('/\s+/', ' ', $time);
$time= str_replace('  ', ' ', $time);
$time= str_replace("Audio Index:","mp3cut -o newfile.mp3",$time);
$time= str_replace("Slow dialog:","-t",$time);
$time= str_replace(" Explanations: ","-",$time);
$time= str_replace("Fast dialog:","oldfile.mp3 ; ",$time);

$time= str_replace("newfile.mp3","rec_".$file[4],$time);
$time= str_replace("oldfile.mp3","".$file[4],$time);

$time = preg_replace('/;.*$/', ' ', $time);


echo trim($title_pod."\n");
echo "\n--------------------------------------\n";
print_r ($texto);

$fp0 = fopen('textos.md', 'a+');
fwrite($fp0,trim("\n".$title_pod."\n"));
fwrite($fp0,"\n--------------------------------------\n");
fwrite($fp0,trim(strip_tags($texto)));
fwrite($fp0,"\n");
fclose($fp0);


$fp1 = fopen('comandos.txt', 'a+');
fwrite($fp1,"\nwget ".$link."\n");
fwrite($fp1,trim($time));
fwrite($fp1,"\n");
fclose($fp1);

} // Fin del for each del principio 
 
?>
