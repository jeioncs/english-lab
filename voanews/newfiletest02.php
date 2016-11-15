
<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

$bd = new SQLite3('../0files/test.db');

$bd->exec('CREATE TABLE voanews (fecha STRING, enlace STRING)');
$bd->exec("INSERT INTO voanews (fecha, enlace) VALUES ('test_fecha', 'Esto es una prueba')");

$results = $bd->query('SELECT * FROM voanews');

while ($row = $results->fetchArray()) {
	var_dump($row);
}

?>
