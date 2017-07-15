<?php

/*
 * An example of a RouteExtender that Extends RouteExtender with more specific
 * methods, properties to a particular part of the site that many routes my share.
 *
 */

namespace Bc\App\RouteExtenders;

use Bc\App\Core\RouteExtenders\DataRouteExtender;
use Bc\App\Core\Util;
use Bc\App\Queries\ArticleDbQueries;
use Bc\App\Services\FormatExampleService;
use Bc\App\Services\ValidateExampleService;

abstract class ExampleRouteExtender extends DataRouteExtender {

    protected $dbName = 'example_data';
    protected $articleQueries;
    protected $defaultTheme = 'bengalcat';

    protected function setTemplatePaths() {
        
        if (!defined('CSS_DIR')) {
            define('CSS_DIR', $this->getThemePart('assets/build/css/'));
            define('JS_DIR', $this->getThemePart('assets/build/js/'));
            define('IMAGE_DIR', $this->getThemePart('assets/build/img/'));
        }
        
        parent::setTemplatePaths();
    }
    
    protected function loadServices()
    {
        parent::loadServices();
        
        $this->validateService = new ValidateExampleService($this);
        $this->formatService = new FormatExampleService($this);

    }

    protected function loadQueries()
    {
        parent::loadQueries();

        $this->articleQueries = new ArticleDbQueries($this->dbName);

        /* @help the same connection for other queries if you have them */
        $db = $this->articleQueries->getDb();
        //$this->otherQueries->setDb($db);
    }

    protected function doCustomInit()
    {
        parent::doCustomInit();

        /* @help Do other things, maybe setup a property from settings: */
        //$this->adminEmail = $this->bc->getSetting('adminEmail', 'example@example.com');
        
    }
}