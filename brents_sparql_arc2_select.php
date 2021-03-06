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

$q = 'SELECT * { ?s ?p ?o . }';

$rows = $store->query($q, 'rows');

print_r($rows);

/**
* foreach ($result as $rows) {
*       echo "<li>". $result . "</li>\n";
*    }
*/
?>
