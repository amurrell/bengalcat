<?php

namespace Bc\App;


class Util {

    /**
     * Print the contents of a variable
     * @param mixed $var The variable to print contents of
     */
    static public function var_print($var) {
        echo '<pre>';print_r($var); echo '</pre>';
    }

    /**
     * Print the contents of a variable with a heading
     * @param mixed $var The variable
     * @param string $heading The heading
     * @param bool $echo_off To toggle this fancy var_print off
     *
     */
    static public function var_hprint($var, $heading = '', $echo_off = false) {
        if ($echo_off) {
            return;
        }
        echo '<h1>' . $heading . '</h1>';
        if ($var === null) {
            return;
        }
        echo '<pre>';print_r($var); echo '</pre>';
    }

    /**
     * Make a curl call
     * @param string $url The url to call with curl
     * @param array $params The postfields to send
     * @param bool $get If using get as the method, default false
     * @return mixed The response from the curl call
     */
    static public function makeCurlCall($url, $params = array(), $get = false) {
        // create a new cURL resource
        $ch = curl_init();

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        // Check for params
        if (!empty($params)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }

        // Enforce get
        if ($get) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        }

        // grab URL and pass it to the browser
        $data = curl_exec($ch);

        // close cURL resource, and free up system resources
        curl_close($ch);

        return $data;

    }

    static public function getClassName($className)
    {
        return preg_replace('/.*'. preg_quote('\\') .'/', '', $className);
    }

    static public function getQueryVars($url = null, $query = null) {
        $query_vars = array();
        $queryData = (empty($url)) ? $query : parse_url($url)['query'];
        parse_str( $queryData, $query_vars );
        return $query_vars;
    }

    static public function trigger404($bc) {
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
        $bc->setRouteClass('Error404');
        $error404page = new \Bc\App\Error404($bc);
        exit();
    }

    static public function triggerError($returnData) {
        header('Content-Type: application/json');
        header($_SERVER["SERVER_PROTOCOL"]." {$returnData['error_code']}", true, $returnData['error_code']);
        echo json_encode($returnData);
        exit();
    }

    static public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
          return 'n-a';
        }

        return $text;
    }

    static public function getTemplateContents($path, $data = null) {

        if (!file_exists($path)) {
            self::triggerError(array(
                'success' => false,
                'error_code' => 424,
                'message' => 'Missing/Incorrect Template File'
            ));
        }

        ob_start();
        include $path;
        return ob_get_clean();
    }


    static public function getImage($imageName) {
        $path = ASSETS_DIR . 'build/img/' . $imageName;
        $relative = str_replace(INDEX_DIR, '/', $path);
        return file_exists($path) ? $relative : '';
    }

}

