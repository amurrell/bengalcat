<?php

/*
 * An example of a RouteExtender that Extends RouteExtender with more specific
 * methods, properties to a particular part of the site that many routes my share.
 *
 */

namespace Bc\App\RouteExtenders;

use Bc\App\Queries\ArticleDbQueries;
use Bc\App\Util;

abstract class SiteRouteExtender extends DataRouteExtender {

    protected $dbName = 'example_data';
    protected $articleQueries;

    protected function loadQueries()
    {
        parent::loadQueries();

        $this->articleQueries = new ArticleDbQueries($this->dbName);

    }
}

