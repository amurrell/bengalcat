<?php

/*
 * An example of a RouteExtender that Extends RouteExtender with more specific
 * methods, properties to a particular part of the site that many routes my share.
 *
 */

namespace Bc\App\Core\RouteExtenders;

use Bc\App\Core\Util;

abstract class CrudRouteExtender extends DataRouteExtender {

    protected $redirect;
    protected $renderData;
    protected $required = [];
    protected $notNull = [];
    protected $boolStrings = [];
    protected $validateFormName = '';

    protected function getRender($path, $data)
    {
        if (!$this->bc->getQueryRequest('html') || !file_exists($path)) {
            return null;
        }

        return Util::getTemplateContents(
            $path,
            $data
        );
    }

    protected function validate()
    {

        $valid = $this->validateForm(
            $this->required,
            $this->notNull,
            $this->boolStrings,
            $this->validateFormName
        );

        if ($valid['success']) {
            return;
        }
        
        $this->triggerError(409, $valid['error']);
    }

    protected function sendResponse($message = 'It worked!', $data = [])
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if (!empty((array) $data)) {
            $response['data'] = $data;
        }

        if (!empty((array) $this->renderData)) {
            $response['htmls'] = $this->renderData;
        }

        if (!empty($this->redirect)) {
            $response['redirect'] = $this->redirect;
        }

        Util::returnJsonResponse($response, 200);
    }
}