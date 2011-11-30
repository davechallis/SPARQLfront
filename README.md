# SPARQLfront #
SPARQLfront is a PHP and javascript based frontend to RDF SPARQL endpoints.

It uses a modified version of [ARC2][1] (a nice PHP RDF library) for most of
the functionality, [Skeleton][2] to provide the basic HTML and stylesheets, and
[CodeMirror][3] to provide syntax highlighting for SPARQL.

## Demo ##
<http://sparql.data.southampton.ac.uk/sparqlfront/>

## Getting started ##
Copy all of the SPARQLfront files into a directory that's visible on the web.

Most of the config is kept directly in index.php.  Options you can set there
there include:

* *remote_store_endpoint* - Set this to the URL of your triplestore's endpoint.
  SPARQL queries will be passed to this.
* *endpoint_features* - Set the operations allowed on this endpoint (load,
  insert, delete and dump disabled by default)
* *endpoint_max_limit* - [Optional] Max number of results that will be returned
  by a query.
* *passthrough_sparqlxml* - Set to true to pass any SPARQL XML returned by a
  store back to the user without being passed through ARC2.  If set to false,
  results are parsed by ARC2, then serialised and sent back to user.
* *sample_queries* - Can set any number of sample queries to be displayed when
  javascript link on the endpoint is clicked.
* *prefixes* - Set namespace prefixes which will populate the query form when
  the Load Namespaces button is clicked.
* *default_query* - Text that is shown in the query form when page is loaded.
* *output_formats* - Formats listed in the Output format dropdown on the side
  of the page.

### Changing styles and HTML ###
The HTML output can be modified by changing lib/template.php.

Stylesheets can be changed either inline in lib/template.php, or by editing
anything in the stylesheets directory.

## Modifications to ARC2 ##
SPARQLfront uses an [ARC2 fork][4] that contains a few modifications. These
include:

* Additional output formats (CSV and SQLite)
* JSON encoding bug fixed
* Added support for SPARQL 1.1 keyword GROUP\_CONCAT and SAMPLE.
* Added RemoteStoreEndpoint class, which allows queries to be passed to any
  triplestore
* Added option to enable SPARQL XML output to be returned directly to client
  without passing through ARC2

## Contact ##
Dave Challis

* Email: <suicas@gmail.com>
* Twitter: [@davechallis](https://twitter.com/#!/davechallis)

[1]: https://github.com/semsol/arc2/wiki
[2]: http://www.getskeleton.com/
[3]: http://codemirror.net/
[4]: https://github.com/davechallis/arc2
