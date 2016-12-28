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

    static public function trigger404($bc) {
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
        $bc->setRouteExtender('Error404');
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

    static public function returnJsonResponse($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    static public function getTemplateContents($path, $data = null) {

        if (!file_exists($path)) {
            self::triggerError(array(
                'success' => false,
                'error_code' => 424,
                'message' => 'Missing/Incorrect Template File: ' . $path
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

}

