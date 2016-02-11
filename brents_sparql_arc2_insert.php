<?php
/* ARC2 static class inclusion */
include_once('./arc2/ARC2.php');

/* configuration */
$config = array(
  /* remote endpoint */
  'remote_store_endpoint' => 'http://localhost/iksce/sparql',

);

/* instantiation */
$store = ARC2::getRemoteStore($config);

print_r($config);

//$q = 'SELECT * { ?s ?p ?o . }';

$query = 'INSERT INTO <http://localhost/iksce/sparql>
{
  <http://example/book1> a "An old book" .
}';

/*
$q = ' INSERT DATA { http://localhost/iksce/taxonomy/term/5 a http://dbpedia.org/resource/duckie . }';

$rows = $store->query($q, 'rows');

print_r($rows);

foreach ($result as $rows) {
       echo "<li>". $result . "</li>\n";
    }
*/
?>
