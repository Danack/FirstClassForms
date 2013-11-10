<?php


namespace Intahwebz\Validator;



class StringLengthOrNull extends \Zend\Validator\StringLength {

    public function isValid($value)
    {
        if ($value == null || strlen(trim($value)) == 0) {
            return true;
        }
     
        return parent::isValid($value);
    }

}

 