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

if (!$store->isSetUp())
  $store->setUp();

$query = 'INSERT INTO <http://localhost/iksce/sparql>
{
  <http://example/book1> a "A lovely book" .
}';

$res = $store->query($query);
echo var_dump($store->getErrors());
echo "<br><br>executed INSERT, returned: ";
echo var_dump($res);
?>
