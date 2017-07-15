<?php

namespace Bc\App\Core\RouteExtenders;

use Bc\App\Core\RouteExtender;
use Bc\App\Core\Services\FormatService;
use Bc\App\Core\Services\ValidateService;
use Bc\App\Utils\NavUtil;

abstract class ExtendedRouteExtender extends RouteExtender {

    protected $nav;
    protected $tokens = [];
    protected $queries = [];
    protected $data = [];
    protected $formData = [];
    protected $validateService;
    protected $formatService;
    protected $defaultTheme;

    protected function customMethods()
    {
        $this->loadQueries();
        $this->loadUtils();
        $this->loadServices();
        $this->doCustomInit();
    }

    protected function loadQueries()
    {

    }

    protected function loadUtils()
    {
        $this->nav = new NavUtil(
            $this->bc,
            $this->bc->getSetting('navItems', []),
            $this->bc->getSetting('navRenderPath'),
            $this->bc->getSetting('navActiveClass')
        );
    }

    protected function loadServices()
    {
        $this->validateService = new ValidateService($this);
        $this->formatService = new FormatService($this);
    }

    protected function doCustomInit()
    {

    }
}
