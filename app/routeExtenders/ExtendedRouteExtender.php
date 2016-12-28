<?php

namespace Bc\App\RouteExtenders;

use Bc\App\RouteExtender;
use Bc\App\Services\FormatService;
use Bc\App\Services\ValidateService;
use Bc\App\Util;
use Bc\App\Utils\NavUtil;

abstract class ExtendedRouteExtender extends RouteExtender {

    protected $nav;
    protected $tokens = [];
    protected $queries = [];
    protected $data = [];
    protected $formData = [];
    protected $validateService;
    protected $formatService;

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
        $this->nav = new NavUtil();
    }

    protected function loadServices()
    {
        $this->validateService = new ValidateService();
        $this->formatService = new FormatService();
    }

    protected function doCustomInit()
    {

    }
}
