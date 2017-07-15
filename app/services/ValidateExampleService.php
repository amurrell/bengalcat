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

use Bc\App\Core\Services\ValidateService;

class ValidateExampleService extends ValidateService {

    protected function validateExampleForm()
    {
        if (
            !is_bool($this->formData->accepted_terms)
        ) {
            return $this->fail('Accepted Terms must be boolean.');
        }
        
        if (
            (!$this->formData->accepted_terms)
        ) {
            return $this->fail('Terms must be accepted.');
        }

        if (
            strlen($this->formData->password) < 8
        ) {
            return $this->fail('Password must be more than 8 characters.');
        }

        return $this->success();
    }

}

