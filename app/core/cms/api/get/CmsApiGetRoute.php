<?php

namespace Bc\App\Core\Cms\Api\Get;

use Bc\App\Core\RouteExtenders\CrudRouteExtender;
use Bc\App\Core\Util;

class CmsApiGetRoute extends CrudRouteExtender {
    
    
    public function init() 
    {
        $this->routeVars = $this->routeData->routeVars;
        
        if ($this->routeVars->route == '/something/else/') {
            $this->triggerError();
        }
        
        $this->sendResponse('Route Exists', (object) [
            'displayType' => 'docs',
        ]);
    }
}
