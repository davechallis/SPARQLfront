<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once(dirname(__FILE__).'/lib/endpoint.php');

/***** Configuration Settings *****/
/*** ARC2 settings ***/
$config['arc'] = array(
    'remote_store_endpoint' => 'http://localhost:8080/sparql/',
    'endpoint_features' => array(
        'select', 'construct', 'ask', 'describe', // allow read
        //'load', 'insert', 'delete',             // disallow update
        //'dump'                                  // disallow backup
    ),
    'endpoint_max_limit' => 2000, /* optional */
    'passthrough_sparqlxml' => true
);
/*** end of ARC2 settings ***/

/*** Sample query settings ***/
/* add any number of these, will be listed above query form, or remove to
 * hide sample queries altogether
 */
$name = 'Get 3 subject URIs';
$query = <<<QUERY_END
SELECT ?subject WHERE {
    ?subject ?p ?o
} LIMIT 3
QUERY_END;
$config['sample_queries'][] = array('name' => $name, 'query' => $query);

$name = 'Get 5 predicate URIs';
$query = <<<QUERY_END
SELECT ?predicate WHERE {
    ?s ?predicate ?o
} LIMIT 5
QUERY_END;
$config['sample_queries'][] = array('name' => $name, 'query' => $query);
/*** End of sample query settings ***/

/*** Query form settings ***/
/* Text that will be show when 'Load Namespaces' button is pressed */
$config['prefixes'] = <<<PREFIXES_END
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX foaf: <http://xmlns.com/foaf/0.1/>
PREFIX dct: <http://purl.org/dc/terms/>
PREFIX geo: <http://www.w3.org/2003/01/geo/wgs84_pos#>
PREFIX owl: <http://www.w3.org/2002/07/owl#>

PREFIXES_END;

/* text that will be shown when page is first visited */
$config['default_query'] = <<<QUERY_END
SELECT ?s ?p ?o WHERE {
    ?s ?p ?o
} LIMIT 10
QUERY_END;

/* Supported output formats (ARC2 should support all of these) */
$config['output_formats'] = array(
    'xml'       => 'SPARQL XML',
    'json'      => 'JSON',
    'plain'     => 'Plain',
    'php_ser'   => 'Serialized PHP',
    'turtle'    => 'Turtle',
    'rdfxml'    => 'RDF/XML',
    'infos'     => 'Query Structure',
    'htmltab'   => 'HTML Table',
    'tsv'       => 'Tab Separated Values (TSV)',
    'csv'       => 'Comma Separated Values (CSV)',
    'plain'     => 'Plain',
    'sqlite'    => 'SQLite database'
);
/*** End of query form settings ***/
/***** End of configuration settings *****/

$ep = ARC2::getStoreEndpoint($config['arc']);
$ep = StoreEndpoint::convert_endpoint($ep, $config);
$ep->go();

?>
