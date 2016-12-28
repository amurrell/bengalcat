<?php

/*
 * Use for formatting things.
 *
 * 1. Format a Value, in a Namespace
 *
 * Write a format method for a key, ie 'timestamp'.
 * Add specificity with a namespace ie 'cats'.
 *
 *  - call formatValueByKeyAndNameSpace('timestamp', '1482443673', 'cats');
 *
 *  - that calls a method you'd need to write: formatTimestampCats
 *
 *
 * 2. Add Formats of Value, in a Namespace
 *
 * Write a method to create an array of additional formats based on the value
 *
 * - call addFormatsByKeyAndNameSpace('timestamp', '1482443673', 'cats');
 *
 * - that calls a method you'd need to write: addFormatsTimestampCats()
 *
 * ----------------------------
 * See examples below!
 *
 */

namespace Bc\App\Services;

class FormatService {

    public function __construct()
    {
        // Set anything else you might want here...
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

    /***************************************************************************
     *
     * Write custom methods below:
     *
     **************************************************************************/

    /**
     * Format key 'TimeStamp' in NameSpace 'Cats' (maybe a query on a cats table?)
     * @param type $value
     */
    protected function formatTimestampCats($value) {
        return date('Y-m-d H:i:s', $value);
    }

    protected function addFormatsTimestampCats($value)
    {
        return [
            'timestamp_verbose_format' => date('l jS \of F Y h:i:s A', $value)
        ];
    }

}

