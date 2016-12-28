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

namespace Bc\App\Services;

class ValidateService {

    protected $form;
    protected $formData;

    public function _construct()
    {
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

    protected function validateEditSite()
    {
        if (
            !is_bool($this->formData->remove_repeats)
        ) {
            return $this->fail('Remove repeats must be boolean.');
        }

        if (
            !is_bool($this->formData->locked)
        ) {
            return $this->fail('Locked must be boolean.');
        }

        return $this->success();
    }

    protected function validateSaveSpecialty()
    {
        return $this->success();
    }

    protected function validateDeleteSpecialty()
    {
        return $this->success();
    }

    protected function validateAddPage()
    {
        return $this->success();
    }

    protected function validateEditPage()
    {
        return $this->success();
    }

    protected function validateDeletePage()
    {
        return $this->success();
    }

}

