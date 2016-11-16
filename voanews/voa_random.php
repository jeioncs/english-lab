<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);


if ($_POST) {
	$base_page=$_POST['fecha'];
}

?>


<form action="" method="post">
    51voa URL:  <input type="text" name="fecha" /><br />
    <input type="submit" value="¡enviar!" />
</form>
<hr>


<?php

?>