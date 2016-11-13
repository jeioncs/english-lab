<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

//mp3cut -o 01_ESL289.mp3 -t 1:24-3:18 ESLPod289.mp3
   
//$page = file_get_contents('https://www.eslpod.com/website/show_podcast.php?issue_id=19021161');

//Creates a blank array.
$id_podcast = array();

//Creates an array with elements.
$id_podcast = array("2615211","2625154","2645040","2654983","2674869","2684812","2694755","2704698","2734527","2744470","2754413","2764356","2794185","2804128","2824014","2833957","3012931","3022874","2963216","2973159","2993045","3002988","3042760","3052703","3062646","3072589","3191905","3201848","3112361","3122304","3152133","3132247","3162076","3172019","3221734","3231677","3251563","3261506","3291335","3301278","3321164","3331107","3350993","3360936","3380822","3390765","3420594","3410651","3440480","3450423","3470309","3480252","3510081","3520024","3529967","3539910","3569739","3579682","3589625","3599568","3619454","3629397","3649283","3659226","3679112","3689055","3708941","3718884","3738770","3748713","3768599","3778542","3808371","3818314","3828257","3838200","3858086","3868029","3897858","3907801","3927687","3937630","3957516","3967459","3987345","3997288","4017174","4027117","4047003","4056946","4076832","4086775","4106661","4116604","4156376","4136490","4146433","4196148","4206091","4216034");


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
