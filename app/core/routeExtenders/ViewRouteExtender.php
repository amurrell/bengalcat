<?php

/*
 * An example of a RouteExtender that Extends RouteExtender with more specific
 * methods, properties to a particular part of the site that many routes my share.
 *
 */

namespace Bc\App\Core\RouteExtenders;

use Bc\App\Core\Util;

abstract class ViewRouteExtender extends ExtendedRouteExtender {

    protected function doCustomInit()
    {
        parent::doCustomInit();

        $this->nav->setAddItemsLast(false);
    }
}

