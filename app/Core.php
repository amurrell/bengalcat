<?php

namespace Bc\App;

use Bc\App\Util;
use Exception;

class Core {

    public $util;

    private $dir;
    private $queryParams;
    private $queryParamsString;
    private $queryString;
    private $queryVars;
    private $method;
    private $routes;
    private $route;
    private $routeClass;
    private $routeClassPath;
    private $routeAction;
    private $routeVariant;

    public function __construct($dir)
    {
        $this->dir = $dir;

        define('INDEX_DIR', $dir . '/');
        define('CLASSES_DIR', $dir . '/classes/');
        define('SRC_DIR', $dir . '/src/');
        define('ASSETS_DIR', $dir . '/assets/');

        $this->loadUtil()
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
                    $this->routeClassPath = $configuredClass;
                    break;
                }
            }
        }

        // Matches exact routes
        if (isset($this->routes[$this->route])) {
            $this->routeClassPath = $this->routes[$this->route];
        }

        if (empty($this->routeClassPath)) {
            $this->util->trigger404($this);
        }
        else {
            $this->routeClass = $this->util->getClassName($this->routeClassPath);
        }

        return $this;
    }

    protected function newRoute() {
        
        try {
            if (!class_exists($this->routeClassPath)) {
                throw new Exception();
            }
            
            $this->routeAction = new $this->routeClassPath($this);
        } catch (Exception $e) {
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

    public function getRouteClass() {
        return $this->routeClass;
    }

    public function getRouteClassPath() {
        return $this->routeClassPath;
    }

    public function getRouteAction() {
        return $this->routeAction;
    }

    public function getRouteVariant() {
        return $this->routeVariant;
    }

    public function setRouteClass($routeClass, $force = false)
    {
        // Only set if it is empty or we consciously did this.
        if (empty($this->routeClass) || $force) {
            $this->routeClass = $routeClass;
        }
        return $this;
    }
}

