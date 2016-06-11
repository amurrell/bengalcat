<?php

namespace Bc\App;

use \Bc\App\Util;

class Core {

    public $util;

    private $dir;
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
        $this->queryVars = $this->util->getQueryVars(
                null,
                $this->queryString);

        $this->route = str_replace('?' . $this->queryString,
                '',
                $_SERVER['REQUEST_URI']);

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
        $this->routeAction = new $this->routeClassPath($this);

        return $this;
    }


    public function getDir() {
        return $this->dir;
    }

    public function getQueryString() {
        return $this->queryString;
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

    public function setRouteClass($routeClass)
    {
        // Only set if it is empty
        if (empty($this->routeClass)) {
            $this->routeClass = $routeClass;
        }
        return $this;
    }
}

