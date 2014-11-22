<?php

namespace Hydra\Fields\Validators;

use Hydra\Fields\Field;

class ValidatorMail extends Validator {

  public function __construct(Field $field, $message, $value) {
    parent::__construct($field, 'text', $message, TRUE);
  }

  public function validate() {
    $values = $this->getField()->getBuilder()->getValues();

    if (!(bool)filter_var($values[$this->getField()->getName()], FILTER_VALIDATE_EMAIL)) {
      return $this->getMessage();
    }
  }
}
