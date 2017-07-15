<?php

/*
 * Use for formatting things.
 *
 * 1. Format a Value, in a Namespace
 *
 * Write a format method for a key, ie 'timestamp'.
 * Add specificity with a namespace ie 'example'.
 *
 *  - call formatValueByKeyAndNameSpace('timestamp', '1482443673', 'example');
 *
 *  - that calls a method you'd need to write: formatTimestampExample
 *
 *
 * 2. Add Formats of Value, in a Namespace
 *
 * Write a method to create an array of additional formats based on the value
 *
 * - call addFormatsByKeyAndNameSpace('timestamp', '1482443673', 'example');
 *
 * - that calls a method you'd need to write: addFormatsTimestampExample()
 *
 * ----------------------------
 * See examples below or in Services\FormatExampleService.php!
 *
 */

namespace Bc\App\Core\Services;

class FormatService {

    protected $route;
    
    public function __construct($route)
    {
        $this->route = $route; // can only use public methods/props
        return $this;
    }

    public function formatValueByKeyAndNameSpace($key, $value, $nameSpace = '')
    {
        $method = 'format' . ucfirst($key) . ucfirst($nameSpace);

        $newValue = (method_exists($this, $method))
            ? $this->{$method}($value)
            : $value;

        return $newValue;
    }

    public function addFormatsByKeyAndNameSpace($key, $value, $nameSpace = '') {

        $method = 'addFormats' . ucfirst($key) . ucfirst($nameSpace);

        $newValues = (method_exists($this, $method))
            ? $this->{$method}($value)
            : [];

        return $newValues;

    }
}

