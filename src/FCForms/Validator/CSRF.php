<?php

namespace Intahwebz\Validator;

use Zend\Validator\AbstractValidator;

use Intahwebz\FormElement\AbstractElement;
use Intahwebz\Exception\UnsupportedOperationException;
use Intahwebz\FormElement\ElementValidator;

/**
 * Class CSRF Implements a CSRF protection for forms. We can't use Zends as it relies on
 * ZendSession.
 * @package Intahwebz\Validator
 */
class CSRF extends AbstractValidator implements ElementValidator {

	const NOT_VALID           = 'csrfDoesntMatch';

	protected $messageTemplates = array(
		self::NOT_VALID           => "Please confirm submission.",
	);

	/**
	 * Compare the current value of the CSRF element with the stored value.
	 * @param AbstractElement $element
	 * @return bool|mixed
	 */
	function isValidElement(AbstractElement $element){

		if ($element->getValidationValue() == $element->getCurrentValue()) {
			return true;
		}

		$this->error(self::NOT_VALID);

		return false;
	}

    /**
     * This should never be called, the version exposed through ElementValidator should be used.
     * @param mixed $value
     * @throws UnsupportedOperationException
     * @return bool
     */
	function isValid($value){
		throw new UnsupportedOperationException("Bad call, don't use isValid use isValidElement.");
	}
}


