<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once(dirname(__FILE__).'/arc2/ARC2.php');
require_once(dirname(__FILE__).'/arc2/store/ARC2_RemoteStoreEndpoint.php');

class StoreEndpoint extends ARC2_RemoteStoreEndpoint {
    private $config;

    public function getHTMLDocOptions() {
        $sel = $this->p('output');
        if (!$sel) {
            $sel = 'htmltab';
        }
        $sel_code = ' selected="selected"';

        $formats = $this->config['output_formats'];

        $html = '<select id="output" name="output">';
        foreach ($formats as $k => $v) {
            $selected = '';
            if ($sel == $k) {
                $selected = $sel_code;
            }

            $html .= "<option value='$k' $selected>$v</option>\n";
        }
        $html .= '</select>';
        return $html;
    }

    public function getHTMLDocTitle() {
        return '';
    }

    public function getHTMLDocHeading() {
        return '';
    }

    public function getHTMLDocCSS() {
        return '';
    }

    public function getHTMLDocHead() {
        return '';
    }

    public function getHTMLFormDoc() {
        return $this->getHTMLDocBody();
    }

    public function getHTMLDocForm() {
        if ($this->p('query')) {
            $q = htmlspecialchars($this->p('query'));
        }
        else {
            $q = $this->config['default_query'];
        }

        $options = $this->getHTMLDocOptions();
        return <<<FORM_END
<textarea id="query" style="width: 100%;" name="query" rows="20" cols="80">$q</textarea>
FORM_END;
    }

    public function getHTMLTableSelectResultDoc($r) {
        $vars = $r['result']['variables'];
        $rows = $r['result']['rows'];
        $dur = $r['query_time'];

        return '<table>'
             . $this->getHTMLTableRows($rows, $vars)
             . '</table>'
             ;
    }

    public function getHTMLDocBody() {
        $heading = $this->getHTMLDocHeading();
        $features = implode(', ', $this->getFeatures());
        $max_results = $this->v(
            'endpoint_max_limit', '<em>unrestricted</em>', $this->a
        );
        $query_form = $this->getHTMLDocForm();
        $results = '';
        if ($this->p('show_inline')) {
            $results = $this->query_result;
            if ($results != '') {
                $results = '<h1>Query Results</h1>' . $results;
            }
        }

        /* template variables */
        $endpoint_features = $features;
        $endpoint_max_results = $max_results;

        $method = 'post';
        if (isset($_SERVER['REQUEST_METHOD'])
            && $_SERVER['REQUEST_METHOD'] == 'GET') {
            $method = 'get';
        }

        $query_form_open = <<<QUERY_FORM_OPEN
<form id="sparql-form"
      action="#results_table"
      enctype="application/x-www-form-urlencoded"
      method="$method">
QUERY_FORM_OPEN;

        $query_options = $this->getHTMLDocOptions();

        $jsonp_input = '<input type="text" id="jsonp" name="jsonp" value="' . htmlspecialchars($this->p('jsonp')) . '" />';

        $checked = '';
        $show_inline = $this->p('show_inline');
        if ($show_inline !== 0) {
            $checked = " checked='checked'";
        }

        $show_inline_input = "<input type='checkbox' name='show_inline' value='1' $checked />";

        if ($method == 'get') {
            $get_checked = 'checked="checked"';
            $post_checked = '';
        }
        else {
            $post_checked = 'checked="checked"';
            $get_checked = '';
        }

        $http_method_input = <<<HTTP_METHOD_END
<label for="http_method_get">
    <input name="http_method"
           class="http_method_radio"
           type="radio"
           value="get" $get_checked
           id="http_method_get" />
    <span>get</span>
</label>

<label for="http_method_post">
    <input name="http_method"
           class="http_method_radio"
           type="radio"
           value="post" $post_checked
           id="http_method_post" />
    <span>post</span>
</label>
HTTP_METHOD_END;

        $namespace_prefixes = json_encode($this->config['prefixes']);

        $sample_queries = '';
        if (isset($this->config['sample_queries'])) {
            foreach ($this->config['sample_queries'] as $sq) {
                $url = '?show_inline=1&amp;query='
                     . urlencode($sq['query'])
                     . '#results_table'
                     ;

                $sample_queries .= "<li><a href='$url'>{$sq['name']}</a></li>";
            }
        }

        ob_start();
        include('template.php');
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    public function setConfig($config) {
        $this->config = $config;
    }

    public static function convert_endpoint($ep, $config) {
        $custom_ep = __CLASS__;
        $ser_custom_ep = strlen($custom_ep) . ':"' . $custom_ep;
        $ser_ep = serialize($ep);
        $ser_ep = preg_replace(
            '/18:"ARC2_StoreEndpoint/', $ser_custom_ep, $ser_ep
        );
        $ep = unserialize($ser_ep);
        $ep->setConfig($config);
        return $ep;
    }
}
