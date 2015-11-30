<?php

namespace FCFormsTest;

use FCForms\Validator\CSRF as CSRFValidator;
use FCForms\FormElement\CSRF as CSRFElement;
use FCForms\FormElement\Element;

class CSRFTest extends BaseTestCase
{
    public function testFirst()
    {
        $form = $this->createEmptyForm();
        $csrfValidator = new CSRFValidator();
        $dataStore = $this->injector->make('FCForms\DataStore');
        $csrfElement = new CSRFElement($form, $dataStore);

        $element = new Element($csrfElement, $rowID = 5);
        $element->prepareToRender();
        $result = $csrfValidator->isValidElement($element);
        $this->assertTrue($result);
    }

    public function testErrors()
    {
        $csrf = new CSRFValidator();
        $this->setExpectedException('FCForms\UnsupportedOperationException');
        $csrf->isValid("foo");
    }
}
