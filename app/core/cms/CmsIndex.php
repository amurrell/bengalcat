<?php

namespace Bc\App\Core\Cms;

use Bc\App\Core\RouteExtender;
use Bc\App\Core\Util;

class CmsIndex extends RouteExtender {
    
    protected $uri;
    protected $settings;
    protected $apiRoute;
    
    protected $matchVars = [
        'cms',
        'create',
        'get',
        'update',
        'delete',
        'route'
    ];
    
    protected function init()
    {
        $this->settings = $this->bc->getSetting('cms');
        $this->uri = $_SERVER['REQUEST_URI'];
        
        $this->isApiCall();
        $this->isRoute();
        $this->triggerError(404, "There are no defined routes in the CMS.");
    }
    
    protected function isApiCall()
    {
        if (!preg_match('#/api/cms/#', $this->uri)) {
            return;
        }
        
        $this->setupApiCall();
        $this->buildApiRoute();
        
        // Bad because it should be matched in routes, not here.
        if (!$this->apiRoute) {
            $this->triggerError(404, "There are no matching defined routes in the CMS API.");
        }
        
        $this->bc->setRouteExtender($this->apiRoute, true);
        
        new $this->apiRoute(
            $this->bc, 
            (object) [
                'routeVars' => $this->routeVars
            ]
        );
        
        exit();
    }

    protected function isRoute()
    {
        $apiRequestUri = Util::getBasePath() . '/api/cms/get/route' . $this->uri;
        
        $response = Util::makeCurlCall($apiRequestUri, [], true, true);
        $data = json_decode($response);
        
        $displayType = $data->data->displayType;
        $controller = $this->getDisplayController($displayType);
        
        if (!$data->success || empty($controller)) {
            return;
        }
        
        $this->bc->setRouteExtender($controller, true);
        
        new $controller(
            $this->bc, 
            $data
        );
        
        exit();
    }
    
    protected function getDisplayController($displayType)
    {
        if (empty($this->settings)) {
            return false;
        }
        
        if (empty($this->settings->displays)) {
            return false;
        }
        
        if (empty($this->settings->displays->$displayType)) {
            return false;
        }
        
        if (empty($this->settings->displays->$displayType->controller)) {
            return false;
        }
        
        return $this->settings->displays->$displayType->controller;
    }
    
    protected function setupApiCall()
    {
        $variants = $this->variants;
        $this->variants = $this->routePieces;
        $this->buildRouteVars();
        $this->variants = $variants;
        
        $this->routeVars->route = (!empty($this->routeVars->route)) 
            ? $this->variants[1] : null;
    }
    
    protected function buildApiRoute()
    {
        $action = $this->routeVars->cms;
        $object = $this->routeVars->$action;
        
        if (empty($action) || empty($object)) {
            $this->apiRoute = false;
            return;
        }
        
        $route = vsprintf('Bc\App\Core\Cms\Api\%s\CmsApi%s%s', [
            ucfirst($action),
            ucfirst($action),
            ucfirst($object),
        ]);
        
        $this->apiRoute = (class_exists($route) ? $route : false);
    }
}

