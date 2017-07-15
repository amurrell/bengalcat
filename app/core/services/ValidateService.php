<?php

/**
 * Edit your validate service however you like.
 *
 * Create a method "validateFormName",
 * which uses $form for FormName - passed to "validateForm"
 *
 * Write the validation in that method. Use $this->success(), or $this->fail()
 * in your method to return result of validation process.
 */

namespace Bc\App\Core\Services;

class ValidateService {

    protected $form;
    protected $formData;
    protected $route;

    public function _construct($route)
    {
        $this->route = $route; // can only use public methods/props
        return $this;
    }

    public function validateForm($formData, $form)
    {
        $this->formData = $formData;
        return $this->{'validate' . $form}();
    }

    protected function success()
    {
        return ['success' => true];
    }

    protected function fail($message)
    {
        return ['success' => false, 'error' => $message];
    }

}

