<?php

namespace Bc\App;

use Bc\App\Util;
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
    private $settings;

    public function __construct($dir)
    {
        $this->dir = $dir;

        define('INDEX_DIR', $dir . '/');
        define('CONTROLLERS_DIR', $dir . '/controllers/');
        define('SRC_DIR', $dir . '/src/');
        define('ASSETS_DIR', $dir . '/assets/');

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

        $this->settings = (object) array_merge($defaultSettings, $settings);

        return $this;
    }

    protected function parseUrl()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->queryString = $_SERVER['QUERY_STRING'];
        $this->queryParamsString = file_get_contents('php://input');
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
            $this->util->trigger404($this);
        }

        // Use regex to match non-exact routes
        if (!isset($this->routes[$this->route])) {

            foreach ($this->routes as $configuredRoute => $configuredClass) {
                $matches = array();
                if (preg_match('#^'. $configuredRoute . '$#', $this->route, $matches)) {
                    $this->routeVariant = $matches[1];
                    $this->routeExtenderPath = $configuredClass;
                    break;
                }
            }
        }

        // Matches exact routes
        if (isset($this->routes[$this->route])) {
            $this->routeExtenderPath = $this->routes[$this->route];
        }

        if (empty($this->routeExtenderPath)) {
            $this->util->trigger404($this);
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
            var_dump($e);
            $this->util->triggerError(
                array(
                    'success' => false,
                    'error_code' => 501,
                    'message' => 'This route has not been fully implemented. Check controller exists.'
                )
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

    public function getQueryParam($param) {
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

    public function getQueryVars() {
        return $this->queryVars;
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
}

