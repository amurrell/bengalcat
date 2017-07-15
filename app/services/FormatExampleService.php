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

use Bc\App\Core\Services\FormatService;

class FormatExampleService extends FormatService {

    /***************************************************************************
     *
     * Write custom methods below:
     *
     **************************************************************************/

    /**
     * Format key 'TimeStamp' in NameSpace 'Example'
     * @param type $value
     */
    protected function formatTimestampExample($value) {
        return date('Y-m-d H:i:s', $value);
    }

    protected function addFormatsTimestampExample($value)
    {
        return [
            'timestamp_verbose_format' => date('l jS \of F Y h:i:s A', $value)
        ];
    }

}

