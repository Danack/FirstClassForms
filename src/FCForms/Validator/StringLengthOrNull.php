<?php


namespace FCForms\Validator;

use \Zend\Validator\StringLength;

class StringLengthOrNull extends StringLength
{
    public function isValid($value)
    {
        if ($value == null || strlen(trim($value)) == 0) {
            return true;
        }
     
        return parent::isValid($value);
    }
}
