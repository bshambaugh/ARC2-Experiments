<?php
    /**
     * Making a SPARQL SELECT query
     *
     * This example creates a new SPARQL client, pointing at the
     * dbpedia.org endpoint. It then makes a SELECT query that
     * returns all of the countries in DBpedia along with an
     * english label.
     *
     * Note how the namespace prefix declarations are automatically
     * added to the query.
     *
     * @package    EasyRdf
     * @copyright  Copyright (c) 2009-2013 Nicholas J Humfrey
     * @license    http://unlicense.org/
     */

    set_include_path(get_include_path() . PATH_SEPARATOR . '../../lib/');
    require_once "../../lib/EasyRdf.php";
    require_once "../html_tag_helpers.php";

    // Setup some additional prefixes for the Drupal Site
    EasyRdf_Namespace::set('schema', 'http://schema.org/');
    EasyRdf_Namespace::set('content', 'http://purl.org/rss/1.0/modules/content/');
    EasyRdf_Namespace::set('dc', 'http://purl.org/dc/terms/');
    EasyRdf_Namespace::set('foaf', 'http://xmlns.com/foaf/0.1/');
    EasyRdf_Namespace::set('og', 'http://ogp.me/ns#');
    EasyRdf_Namespace::set('rdfs', 'http://www.w3.org/2000/01/rdf-schema#');
    EasyRdf_Namespace::set('sioc', 'http://rdfs.org/sioc/ns#');
    EasyRdf_Namespace::set('sioct', 'http://rdfs.org/sioc/types#');
    EasyRdf_Namespace::set('skos', 'http://www.w3.org/2004/02/skos/core#');
    EasyRdf_Namespace::set('xsd', 'http://www.w3.org/2001/XMLSchema#');
    EasyRdf_Namespace::set('owl', 'http://www.w3.org/2002/07/owl#');
    EasyRdf_Namespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
    EasyRdf_Namespace::set('rss', 'http://purl.org/rss/1.0/');
    EasyRdf_Namespace::set('site', 'http://localhost/iksce/ns#');

    $sparql = new EasyRdf_Sparql_Client('http://localhost/iksce/sparql');
?>
<html>
<head>
  <title>EasyRdf Basic Sparql Example</title>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>
<h1>EasyRdf Basic Sparql Example</h1>

<ul>
<?php
 // Initially assume that we do not need to add the Tag to the rdf store
 $newrdftag = "false";

 // Perform SELECT query on RDF store to validate false assumption for variable rdftag
 $result = $sparql->query(
     'SELECT * { ?s schema:isRelatedTo ?o .
      ?o schema:Comment ?m . }'
 );

 // If I can not prove that rdftag is false, that is the SELECT query returns an empty result, then I need to
 // generate and RDF triple for the tag in the form ?o <http://schema.org/comment ?m
 $dummy_array = (array)$result;
 if (empty($dummy_array)) {
   $newrdftag = "true";
   // Find all taxonomy terms in the form of ?o that need to be assigned a URI ?o in the form
   // of a RDF triple ?o <http://schema.org/comment ?m
   $result = $sparql->query(
         'SELECT * { ?s schema:isRelatedTo ?o . }'
   );
   // Find the URI that ?o needs to be assigned to, by dereferencing ?o and screen scraping to find ?m
   // Then return the RDF triple ?o <http://schema.org/comment ?m
    foreach ($result as $row) {
       //  Dereference ?o and remove markup
        $html = implode('', file($row->o));
        $naked = strip_tags($html);

       // Locate URI in result
        $pattern = "/URI:.*http:\/\/.* /";

          $input_str = $naked;


         if (preg_match_all($pattern, $input_str, $matches_out)) {
            $p = $matches_out[0];
            $withComma = implode(" ", $p);

            // Select only the URI from the page and remove surrounding whitespace
           $regex = "/URI:&nbsp;/";
           $new_string = preg_replace($regex, "$2 $1", $withComma);
           $new_string = preg_replace('/\s+/i', '', $new_string);
           echo  $row->o . " http://schema.org/Comment " . $new_string;
  //   Bind the $subject , $predicate , and $object variables
           $subject = '<'.$row->o.'>';
           $predicate = '<http://schema.org/Comment>';
           $object = '<'.$new_string.'>';
    }
  }
}

//  echo $newrdftag;
?>
</ul>

</body>
</html>
