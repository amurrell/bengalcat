<?php

namespace Bc\App\RouteExtenders;

use Bc\App\Util;

abstract class DataRouteExtender extends ExtendedRouteExtender {

    protected $queries = [];
    protected $data = [];
    protected $formData = [];
    protected $validateService;
    protected $formatService;

    protected function addQuery($name, $queryObj, $method, $data = [], $collection = true, $formatNameSpace = '', $validateSet = false)
    {
        $this->queries[$name] = (object) [
            'queryObject' => $queryObj,
            'method' => $method,
            'data' => $data,
            'collection' => $collection,
            'formatNameSpace' => $formatNameSpace,
            'validateSet' => $validateSet
        ];
    }

    protected function prepareData()
    {
        if (!empty($this->queries)) {
            $this->prepareDataSets();
        }

        $this->data = (object) $this->data;

        // Reset the queries
        $this->queries = [];

        return $this;
    }

    protected function prepareDataSets()
    {
        $this->data = (array) $this->data;

        foreach ($this->queries as $propName => $query) {
            $this->data[$propName] = $this->formatQueryResult(
                $this->{$query->queryObject}
                     ->{$query->method}($query->data),
                $query->collection,
                $query->formatNameSpace
            );

            if ($query->validateSet) {
                $this->validateDataSet($propName);
            }

        }

        $this->data = (object) $this->data;
    }

    protected function validateDataSet($dataSetName)
    {
        // $this->data is not an object yet.
        if (isset($this->queries[$dataSetName]) && empty( (array) $this->data[$dataSetName]) ) {
            Util::trigger404($this->bc);
        }
    }

    protected function formatQueryResult($result, $collection = true, $nameSpace = '')
    {
        $route = $this;

        if (!$collection && is_array($result)) {

            if (!isset($result[0])) {
                return (object) $result;
            }

            return (object) $this->formatRow($result[0], $nameSpace);

        }

        if ($result === NULL) {
            return true;
        }

        return array_map(function($row) use ($route) {
            $formattedRow = $route->formatRow($row, $nameSpace);
            return (object) $formattedRow;
        }, $result);
    }

    protected function formatRow($row, $nameSpace)
    {
        $newRow = $row;
        foreach ($row as $key => $value) {

            $newRow[$key] = $this->formatKeyValue(
                $key,
                $value,
                $nameSpace
            );
            $newRow = array_merge(
                $newRow,
                $this->addFormats(
                    $key,
                    $value,
                    $nameSpace
                )
            );

        }

        return $newRow;
    }

    protected function addformats($key, $value, $nameSpace)
    {
        if ($key == 'timestamp') {
            return $this->formatService->addFormatsByKeyAndNameSpace(
                $key,
                $value,
                $nameSpace
            );
        }

        return [];
    }

    protected function formatKeyValue($key, $value, $nameSpace)
    {
        if ($key == 'timestamp') {
            return $this->formatService->formatValueByKeyAndNameSpace(
                $key,
                $value,
                $nameSpace
            );
        }

        return $value;
    }

    /**
     * Validate a form submission
     *
     * <p>Leave first 3 params blank to skip to validation service</p>
     * <p>Skip ValidationService by not naming the form.</p>
     *
     * @param array $required [optional] Fields that are required to pass
     * @param array $notNull [optional] Fields that cannot be null (implies required too)
     * @param array $boolStrings [optional] String Fields that will be filtered as real booleans
     * @param string $form [optional] The name of your form, used in validation service. Leave blank to skip validation service.
     * @return type
     */
    protected function validateForm($required = [], $notNull = [], $boolStrings = [], $form = '')
    {
        $this->formData = (object) $this->bc->getQueryParams();

        $this->validateFilterBooleans($boolStrings);

        if (!$this->validateRequired($required)) {
            return ['success' => false, 'param' => $key, 'error' => ucwords($key) . ' is required'];
        }

        if ($this->validateNotNull($notNull)) {
            return ['success' => false, 'param' => $key, 'error' => ucwords($key) . ' cannot be null'];
        }

        if (!empty($form)) {
            return $this->validateService->validateForm($this->formData, $form);
        }

        return ['success' => true];
    }

    protected function validateFilterBooleans($boolStrings)
    {
        foreach ($boolStrings as $prop) {
            $this->formData->{$prop} = Util::stringBool(
                $this->formData->{$prop}
            );
        }
    }

    protected function validateRequired($required)
    {
        foreach ($required as $key) {
            if (!$this->bc->issetQueryParam($key)) {
                return false;
            }
        }
    }

    protected function validateNotNull($notNull)
    {
        foreach ($notNull as $key) {
            if ($this->bc->isEmptyQueryParam($key) || $this->bc->isNullQueryParam($key)) {
                return false;
            }
        }
    }

    protected function getSinglePropFromCollection($propName, $collection)
    {
        return (empty($collection)) ? [] : array_map(function($item) use ($propName) {
            return $item->{$propName};
        }, $collection);
    }
}

