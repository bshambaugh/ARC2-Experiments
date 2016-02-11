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

    set_include_path(get_include_path() . PATH_SEPARATOR . '../lib/');
    require_once "EasyRdf.php";
    require_once "html_tag_helpers.php";

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
    $result = $sparql->query(
        'SELECT * { ?s schema:isRelatedTo ?o . }'
    );
    foreach ($result as $row) {
    //    echo "$row->o\n<br/><br/>";
        $html = implode('', file($row->o));
        $naked = strip_tags($html);
    //    echo $naked;
    //   $pattern = "/URI:.*http:\/\/dbpedia\.org\/resource\/Perl/";
        $pattern = "/URI:.*http:\/\/.* /";
    //    $pattern = "/URI:.*http:\/\/d";
      // $pattern = "/<div class="field-label">URI:&nbsp;</div>/";
      //  $input_str = $html;
          $input_str = $naked;
      //  echo $input_str;

         if (preg_match_all($pattern, $input_str, $matches_out)) {
            $p = $matches_out[0];
            $withComma = implode(" ", $p);
          //  echo $p;
      //      echo $withComma;

           $regex = "/URI:&nbsp;/";
      //     $new_string = preg_replace($regex, "$2 $1", $withComma);
      //      $regex = "/\r/";
           $new_string = preg_replace($regex, "$2 $1", $withComma);
      //     echo $new_string;
           echo  $row->o . " schema:comment " . $new_string;
           // Try doing a SPARQL insert here
    }

        //echo $html;
      //  $taxonomysite = file_get_contents($row->o);
       // This prints the html file and the line numbers..
      //  $lines = file($row->o);
      //  foreach ($lines as $line_num => $line) {
      //      echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";
      //  }
        // end of printing the html file and the line numbers..
     //    echo $taxonomysite;
    }
?>
</ul>

</body>
</html>
