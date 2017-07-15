<?php

namespace Bc\App\Core\RouteExtenders;

use Bc\App\Core\RouteExtenders\ExtendedRouteExtender;
use Bc\App\Core\Util;

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

    protected function insertAndSelect
    (
        $db,
        $insert,
        $insertData,
        $select,
        $selectData,
        $collection = false,
        $namespace = '',
        $rollbacks = [],
        $error_message = 'Something went wrong with the data insert!
            Contact an administrator for further assitance.'
    )
    {
        $qd = (object) [
            'db' => $db,
            'insert' => $insert,
            'insertData' => $insertData,
            'select' => $select,
            'selectData' => $selectData,
            'collection' => $collection,
            'namespace' => $namespace,
            'rollbacks' => $rollbacks,
            'message' => $error_message,
            'error' => 500
        ];

        $this->{$qd->db}->{$qd->insert}($qd->insertData);



        $selected = $this->{$qd->db}->{$qd->select}($qd->selectData);
        $data = $this->formatQueryResult($selected, $qd->collection, $qd->namespace);

        if (empty((array) $data)) {
            $this->rollbackDbs($qd->rollbacks);
            Util::triggerError([
                'success' => false,
                'error_code' => $qd->error,
                'message' => $qd->message,
                'data' => $qd
            ]);
        }

        return $data;
    }

    protected function rollbackDbs($dbs) {
        foreach ($dbs as $db) {
            $this->{$db}->rollBack();
        }
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

        return array_map(function($row) use ($route, $nameSpace) {
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
        return $this->formatService->addFormatsByKeyAndNameSpace(
            $key,
            $value,
            $nameSpace
        );
    }

    protected function formatKeyValue($key, $value, $nameSpace)
    {
        return $this->formatService->formatValueByKeyAndNameSpace(
            $key,
            $value,
            $nameSpace
        );
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
        $this->formData = (object) (
            !empty($_FILES)
            ? array_merge( $this->bc->getQueryRequests(), $_FILES)
            : $this->bc->getQueryRequests()
        );

        $this->validateFilterBooleans($boolStrings);

        if (!empty($key = $this->validateRequired($required))) {
            return ['success' => false, 'param' => $key, 'error' => Util::cleanSnakeCase($key) . ' is required'];
        }

        if (!empty($key = $this->validateNotNull($notNull))) {
            return ['success' => false, 'param' => $key, 'error' => ucwords(Util::cleanSnakeCase($key)) . ' cannot be null'];
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
            if (!$this->bc->issetQueryRequest($key)) {
                return $key;
            }
        }
    }

    protected function validateNotNull($notNull)
    {
        foreach ($notNull as $key) {
            if ($this->bc->isEmptyQueryRequest($key) || $this->bc->isNullQueryRequest($key)) {
                return $key;
            }
        }
    }

    protected function getSinglePropFromCollection($propName, $collection)
    {
        return (empty($collection)) ? [] : array_map(function($item) use ($propName) {
            return is_array($item) ? $item[$propName] : $item->{$propName};
        }, $collection);
    }

    protected function getDataProp($propName, $default = null)
    {
        if (empty((array) $this->data)) {
            return $default;
        }

        if (isset($this->data->{$propName})) {
            return $this->data->{$propName};
        }

        return $default;
    }

    protected function getSubDataProp($propName, $subPropName, $default = null)
    {
        if (empty((array) $this->data)) {
            return $default;
        }

        if (!isset($this->data->{$propName})) {
            return $default;
        }

        if (empty((array) $this->data->{$propName})) {
            return $default;
        }

        if (isset($this->data->{$propName}->{$subPropName})) {
            return $this->data->{$propName}->{$subPropName};
        }

        return $default;
    }
}

