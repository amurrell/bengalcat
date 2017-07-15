<?php

namespace Bc\App\Core;

use Bc\App\Core\Util;
use Exception;

class Core {

    public $util;

    private $dir;
    private $queryString;
    private $queryParams;
    private $queryParamsString;
    private $queryVars;
    private $method;
    private $routes;
    private $route;
    private $routeExtender;
    private $routeExtenderPath;
    private $routeAction;
    private $routeVariant;
    private $routeVariants;
    private $routePieces;
    private $settings;
    private $defaultTheme;
    private $themes;

    public function __construct($dir)
    {
        $this->dir = $dir;
        
        $this->defaultTheme = $this->getSetting('defaultTheme', 'bengalcat');

        define('INDEX_DIR', $dir . '/');
        define('CONTROLLERS_DIR', $dir . '/controllers/');
        define('THEMES_DIR', $dir . '/themes/');
        
        $this->themes = $this->getThemes();
        
        $this->loadUtil()
             ->loadSettings()
             ->parseUrl()
             ->redirectRoute()
             ->findRoute()
             ->newRoute();
    }

    protected function loadUtil()
    {
        $this->util = new Util;

        return $this;
    }

    protected function loadSettings()
    {
        $defaultSettings = [];
        $settings = [];

        if (file_exists(APP_DIR . 'config/settingsDefaults.php')) {
            $defaultSettings = include_once APP_DIR . 'config/settingsDefaults.php';
        }

        if (file_exists(APP_DIR . 'config/settings.php')) {
            $settings = include_once APP_DIR . 'config/settings.php';
        }

        $merged = array_merge($defaultSettings, $settings);
        
        $this->settings = Util::objectifyAssocArray($merged, true);
        
        return $this;
    }

    protected function parseUrl()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->queryString = $_SERVER['QUERY_STRING'];
        $this->queryParamsString = file_get_contents('php://input');

        // php://input is not available with enctype="multipart/form-data"
        if (!empty($_POST) && empty($this->queryParamsString)) {
            $this->queryParamsString = http_build_query($_POST);
        }

        $this->queryParams = $this->util->getQueryVars(
            null,
            $this->queryParamsString
        );
        $this->queryVars = $this->util->getQueryVars(
            null,
            $this->queryString);

        $this->route = str_replace('?' . $this->queryString,
            '',
            $_SERVER['REQUEST_URI']
        );

        return $this;
    }

    protected function redirectRoute()
    {
        if (substr($this->route, -1) !== '/') {
            header("Location: $this->route/");
            die();
        }

        return $this;
    }

    protected function findRoute()
    {
        $this->routes = include_once APP_DIR . 'config/routes.php';

        if (empty($this->routes)) {
            $this->util->triggerError(
                $this,
                $this->getSetting('errorRoute'),
                [
                    'success' => false,
                    'error_code' => 404,
                    'message' => "There are no defined routes."
                ]
            );
        }

        // Use regex to match non-exact routes
        if (!isset($this->routes[$this->route])) {

            foreach ($this->routes as $configuredRoute => $configuredClass) {
                $matches = array();
                if (preg_match('#^'. $configuredRoute . '$#', $this->route, $matches)) {
                    $this->routeVariant = $matches[1];
                    $this->routeVariants = $matches;
                    $this->routeExtenderPath = $configuredClass;
                    break;
                }
            }
        }

        $this->routePieces = $this->util->getRoutePieces($this->route);
        
        // Matches exact routes
        if (isset($this->routes[$this->route])) {
            $this->routeExtenderPath = $this->routes[$this->route];
        }
        
        if (empty($this->routeExtenderPath)) {
            $this->util->triggerError(
                $this,
                $this->getSetting('errorRoute'),
                [
                    'success' => false,
                    'error_code' => 404,
                    'message' => "Route {$this->route} has no defined path."
                ]
            );
        }
        else {
            $this->routeExtender = $this->util->getClassName($this->routeExtenderPath);
        }

        return $this;
    }

    protected function newRoute() {

        try {
            if (!class_exists($this->routeExtenderPath)) {
                throw new Exception();
            }
            
            $this->routeAction = new $this->routeExtenderPath($this);
        } catch (Exception $e) {
            $this->util->triggerError(
                $this,
                $this->getSetting('errorRoute'),
                [
                    'success' => false,
                    'error_code' => 501,
                    'message' => 'This route has not been fully implemented. Check controller exists.'
                ]
            );
        }

        return $this;
    }


    public function getDir() {
        return $this->dir;
    }

    public function getQueryString() {
        return $this->queryString;
    }

    public function getQueryParamsString() {
        return $this->queryParamsString;
    }

    public function getQueryParams() {
        return $this->queryParams;
    }

    public function getQueryRequests() {
        return array_merge(
            $this->queryVars,
            $this->queryParams
        );
    }

    public function getQueryRequest($request) {
        return
            isset($this->getQueryRequests()[$request])
                ? $this->getQueryRequests()[$request]
                : null;
    }

    public function issetQueryRequest($request) {
        return isset($this->getQueryRequests()[$request]);
    }

    public function isEmptyQueryRequest($request) {
        return empty($this->getQueryRequests()[$request]);
    }

    public function isNullQueryRequest($request) {
        return ($this->issetQueryRequest($request)) ?
            ($this->getQueryRequests()[$request] === null) :
            true;
    }

    public function getQueryParam($param) {
        if (!$this->issetQueryParam($param)) {
            return null;
        }

        return $this->queryParams[$param];
    }

    public function issetQueryParam($param) {
        return isset($this->queryParams[$param]);
    }

    public function isEmptyQueryParam($param) {
        return empty($this->queryParams[$param]);
    }

    public function isNullQueryParam($param) {
        return ($this->issetQueryParam($param)) ?
            ($this->queryParams[$param] === null) :
            true;
    }

    public function issetQueryVar($var) {
        return isset($this->queryVars[$var]);
    }

    public function isEmptyQueryVar($var) {
        return empty($this->queryVars[$var]);
    }

    public function isNullQueryVar($var) {
        return ($this->issetQueryVar($var)) ?
            ($this->queryVars[$var] === null) :
            true;
    }

    public function getQueryVars() {
        return $this->queryVars;
    }

    public function getQueryVar($var) {
        if (!$this->issetQueryVar($var)) {
            return null;
        }

        return $this->queryVars[$var];
    }

    public function getMethod() {
        return $this->method;
    }

    public function getRoutes() {
        return $this->routes;
    }

    public function getRoute() {
        return $this->route;
    }

    public function getRouteExtender() {
        return $this->routeExtender;
    }

    public function getRouteExtenderPath() {
        return $this->routeExtenderPath;
    }

    public function getRouteAction() {
        return $this->routeAction;
    }

    public function getRouteVariant() {
        return $this->routeVariant;
    }

    public function getRouteVariants() {
        return $this->routeVariants;
    }
    
    public function getRoutePieces() {
        return $this->routePieces;
    }

    public function setRouteExtender($routeExtender, $force = false)
    {
        // Only set if it is empty or we consciously did this.
        if (empty($this->routeExtender) || $force) {
            $this->routeExtender = $routeExtender;
        }
        return $this;
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function getSetting($prop, $default = null)
    {
        if (!isset($this->settings->$prop)) {

            // try get_env first
            if (!empty(getenv($prop))) {
                return getenv($prop);
            }

            return $default;
        }

        return $this->settings->$prop;
    }
    
    public function getDefaultTheme()
    {
        return $this->defaultTheme;
    }
    
    public function getThemes($forceReload = false)
    {
        if (!file_exists(THEMES_DIR)) {
            error_log('You must create a "themes" folder in /html to use themes.');
            return [];
        }
     
        if (!empty($this->themes) && !$forceReload) {
            return $this->themes;
        }
        
        $dirs = scandir(THEMES_DIR);
        
        $themeFolders = array_values(
            array_filter($dirs, function($item) {
                return !in_array($item, ['..', '.']) 
                        && is_dir(THEMES_DIR . $item) 
                        && file_exists(THEMES_DIR . $item); 
            })
        );
            
        $paths = array_map(function($folder) {
            return THEMES_DIR . $folder . '/';
        }, $themeFolders);
        
        return array_combine($themeFolders, $paths);
    }
    
    public function themeExists($theme)
    {
        return isset($this->getThemes()[$theme]);
    }
    
    public function getThemePath($theme, $append = '')
    {
        return $this->themeExists($theme) 
                ? $this->getThemes()[$theme] . $append
                : '';
    }
}