<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

$base_page="http://www.51voa.com/VOA_Special_English/smart-phones-and-other-devices-are-giving-students-many-new-ways-to-cheat-72267.html";

if ($_POST) {
	$base_page=$_POST['url'];
}

?>



<form action="" method="post">
    51voa URL:  <input type="text" name="url" /><br />
    <input type="submit" value="¡enviar!" />
</form>
<hr>


<?php

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


//$menubar = $doc->getElementById('menubar');


$content = $doc->getElementById('content');
$temp_dom1 = new DOMDocument();
$temp_dom1->appendChild($temp_dom1->importNode($content,TRUE)) ;
$texto= str_replace("","",$temp_dom1->saveHTML());


print_r ($playbar);
echo trim($titulo."<br>\n");
print_r ($texto);


 
?>