<?php

namespace Bc\App\Core;

use DateTime;
use DateTimeZone;

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

    static public function getHttpCodeWithCurl($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch,  CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        /* Get the HTML or whatever is linked in $url. */
        $response = curl_exec($ch);

        /* Check for 404 (file not found). */
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return $httpCode;
    }

    static public function getHttpCodesWithCurl($urls, $options = array())
    {
        $curly = array();
        $result = array();

        $mh = curl_multi_init();

        // loop through $data and create curl handles
        // then add them to the multi-handle
        foreach ($urls as $index => $url) {

            $curly[$index] = curl_init();

            $url = (is_array($url) && !empty($url['url'])) ? $url['url'] : $url;
            curl_setopt($curly[$index], CURLOPT_URL, $url);
            curl_setopt($curly[$index], CURLOPT_HEADER, 0);
            curl_setopt($curly[$index], CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curly[$index], CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curly[$index], CURLOPT_TIMEOUT, 5);

            // post?
            if (is_array($url)) {
                if (!empty($url['post'])) {
                    curl_setopt($curly[$index], CURLOPT_POST, 1);
                    curl_setopt($curly[$index], CURLOPT_POSTFIELDS, $d['post']);
                }
            }

            // extra options?
            if (!empty($options)) {
                curl_setopt_array($curly[$index], $options);
            }

            curl_multi_add_handle($mh, $curly[$index]);
        }

        // execute the handles
        $running = null;
        do {
          curl_multi_exec($mh, $running);
        } while ($running > 0);


        // get content and remove handles
        foreach ($curly as $index => $c) {
            $result[$index] = $httpCode = curl_getinfo($c, CURLINFO_HTTP_CODE);
            curl_multi_remove_handle($mh, $c);
        }

        // all done
        curl_multi_close($mh);

        return $result;
    }

    /**
     * Make a curl call
     * @param string $url The url to call with curl
     * @param array $params The postfields to send
     * @param bool $get If using get as the method, default false
     * @return mixed The response from the curl call
     */
    static public function makeCurlCall($url, $params = array(), $get = false, $json = false) {
        // create a new cURL resource
        $ch = curl_init();

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        if ($json) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        }

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

    static public function getHttpStatusHeaderByCode(
        $code,
        $status = 'No Status Message'
    ) {
        $statusCodes = [
            100 => 'Continue',
            101 => 'Switching Protocols',
            102 => 'Processing',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            207 => 'Multi-Status',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            422 => 'Unprocessable Entity',
            423 => 'Locked',
            424 => 'Failed Dependency',
            426 => 'Upgrade Required',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            506 => 'Variant Also Negotiates',
            507 => 'Insufficient Storage',
            509 => 'Bandwidth Limit Exceeded',
            510 => 'Not Extended'
        ];
        
        return isset($statusCodes[$code]) 
            ? $code . ' ' . $statusCodes[$code]
            : $code . ' ' . $status;
    }
    
    static public function triggerError(
        Core $bc,
        $errorRoute = 'ErrorDefault',
        $returnData = []
    ) {
        
        $attemptedPath = $_SERVER['REQUEST_URI'];
        
        $identifiers = $bc->getSetting('errorJsonIdentifiers', []);
        $identifiersMatch = preg_match(
            '#'. implode( '|', array_map('preg_quote', $identifiers) ) . '#',
            $attemptedPath
        );
        
        $useJson = $identifiersMatch; 
        
        $code = (int) $returnData['error_code'];
        $status = isset($returnData['status']) ? $returnData['status'] : null;
        $statusString = self::getHttpStatusHeaderByCode($code, $status );
        
        self::errorLog($returnData, $code, $returnData['message']);
        
        // Send Header
        header($_SERVER["SERVER_PROTOCOL"]." {$statusString}", true, $code);
        
        // Display only JSON
        if ($useJson) {
            header('Content-Type: application/json');
            echo json_encode($returnData);
            exit();
        }
        
        // Use Error Route (view)
        $bc->setRouteExtender($errorRoute, true);
        
        new $errorRoute(
            $bc, 
            array_merge($returnData, ['status' => $statusString])
        );
        
        exit();
    }

    static public function debugError($message, $data = [])
    {
        self::debugLog($data, $message);
        self::triggerError([
            'success' => false,
            'error_code' => '503',
            'message' => 'Temporarily down - debugging: ' . $message,
            'data' => $data,
        ]);
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

    static public function returnJsonResponse($data, $code = 200)
    {
        header('Content-Type: application/json');
        header($_SERVER["SERVER_PROTOCOL"]." $code", true, $code);
        echo json_encode($data);
        exit();
    }

    static public function getTemplateContents($bc, $path, $data = null) {

        if (!file_exists($path)) {
            self::triggerError(
                $bc,
                $bc->getSetting('errorRoute'),
                [
                    'success' => false,
                    'error_code' => 424,
                    'message' => 'Missing/Incorrect Template File: ' . $path
                ]
            );
        }

        ob_start();
        include $path;
        return ob_get_clean();
    }

    static public function getAsset($path, $fileName) {
        $path = $path . $fileName;
        $relative = str_replace(INDEX_DIR, '/', $path);
        
        if (!file_exists($path)) {
            self::errorLog([
                'path' => $path,
                'relative' => $relative
            ], 409, 'File does not exist.');
            return '';
        }
        
        return $relative;
    }

    static public function getPageRangeShowingOnPage(
        $pageNum,
        $perPage,
        $totalItemsNum
    ) {
        $pageHighMax = $pageNum * $perPage;
        $high = ($pageHighMax > $totalItemsNum) ? $totalItemsNum : $pageHighMax;
        $low = $pageHighMax - ($perPage - 1);
        return "$low - $high";
    }

    static public function getBasePath()
    {
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off')
            ? 'https'
            : 'http';

        $domain = $protocol . '://' . $_SERVER['HTTP_HOST'];

        return "$domain";
    }

    static public function getNewPath($addPath)
    {
        $base = self::getBasePath();

        return "{$base}{$addPath}";
    }

    static public function redirect($fullPath)
    {
        header('Location: ' . $fullPath);
        exit();
    }

    static public function redirectShortPath($path)
    {
        header('Location: ' . self::getNewPath($path));
        exit();
    }

    static public function stringBool($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    static public function mergeArrayofArrays($array, $property = null)
    {
        return array_reduce(
            (array) $array, // make sure this is an array too, or array_reduce is mad.
            function($carry, $item) use ($property) {

                $mergeOnProperty = (!$property) ?
                        $item :
                        (is_array($item) ? $item[$property] : $item->$property);

                return is_array($mergeOnProperty)
                    ? array_merge($carry, $mergeOnProperty)
                    : $carry;
        }, array()); // start the carry with empty array
    }

    static public function insertInArrayAfterKey($insertArr, $searchkey, $haystackArr)
    {
        $newArr = [];
        $assoc = is_numeric($searchkey);

        $haystackArr = ($assoc)
                ? $haystackArr
                : array_values($haystackArr);

        foreach ($haystackArr as $key => $value) {

            $newArr[$key] = $value;

            if ($key == $searchkey) {
                $newArr = array_merge($newArr, $insertArr);
            }
        }
        return ($assoc) ? $newArr : array_values($newArr);
    }

    static public function insertInArrayBeforeKey($insertArr, $searchkey, $haystackArr)
    {
        $newArr = [];
        $assoc = is_numeric($searchkey);

        $haystackArr = ($assoc)
                ? $haystackArr
                : array_values($haystackArr);

        foreach ($haystackArr as $key => $value) {

            if ($key == $searchkey) {
                $newArr = array_merge($newArr, $insertArr);
            }

            $newArr[$key] = $value;
        }
        return ($assoc) ? $newArr : array_values($newArr);
    }

    static public function getTimeStampByStrAndTimeZone($str, $timeZone = '')
    {
        $useTimeZone = (empty($timeZone)) ? date_default_timezone_get() : $timeZone;
        $date = new DateTime($str, new DateTimeZone($useTimeZone));
        return $date->format('U');
    }

    static public function cleanSnakeCase($str)
    {
        return str_replace('_', ' ', $str);
    }

    static public function jsonForHtmlAttributes($data)
    {
        return htmlentities(json_encode($data));
    }

    static function getWordSnippet( $str, $wordCount = 10, $ellipsis = false )
    {
        $snippet = implode(
            '', array_slice(
                preg_split(
                    '/([\s,\.;\?\!]+)/', strip_tags($str),
                    $wordCount * 2 + 1, PREG_SPLIT_DELIM_CAPTURE
                ), 0, $wordCount * 2 - 1
            )
        );
        return ($ellipsis && strlen($str) > strlen($snippet))
            ? $snippet.'...'
            : $snippet;
    }

    static function getStringSnippet($str, $char = 20, $ellipsis = false)
    {
        $len = !$ellipsis ? $char : $char - 3;
        $ellipsis = !$ellipsis ? '' : '...';

        return (strlen($str) >= $char)
            ? substr($str,0, $len) . $ellipsis
            : $str;
    }

    static function objectifyArray($arr)
    {
        return json_decode(json_encode($arr));
    }

    static public function arrayifyObject($obj)
    {
        if (!is_object($obj) && !is_array($obj)) {
            return $obj;
        }

        $arr = (array) $obj;

        if (!empty($arr)) {
            return array_map('self::arrayifyObject', $arr);
        }

        return $arr;
    }
    
    static public function objectifyAssocArray($arr, $recursive = false)
    {
        if (!is_array($arr)) {
            return $arr;
        }
        
        if ($recursive) {
            $arr = array_map(function($item) use ($recursive) {
                return self::objectifyAssocArray($item, $recursive);
            }, $arr);
        }
        
        return (!is_int(key($arr))) ? (object) $arr : $arr;
    }
    
    static public function errorLog(
        $data,
        $errorCode = 500,
        $errorMessage = 'No error message specified.'
    ) {
        error_log(json_encode([
            'error' => true,
            'code' => $errorCode,
            'message' => $errorMessage,
            'data' => $data
        ]));
    }
    
    static public function debugLog(
        $data,
        $debugMessage = 'No debug message specified.'
    ) {
        debugLog(json_encode([
            'debug' => true,
            'code' => 503,
            'message' => $debugMessage,
            'data' => $data
        ]));
    }
    
    static public function getRoutePieces($route)
    {
        $variants = explode('/', $route);
        return array_values(array_filter(
            $variants,
            function($variant){
                return strlen($variant);
            }
        ));
    }
}

