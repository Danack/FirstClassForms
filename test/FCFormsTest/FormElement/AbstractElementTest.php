<?php

namespace FCFormsTest;

use Auryn\Injector;
use FCFormsTest\BaseTestCase;

abstract class AbstractElementTest extends BaseTestCase
{
    /**
     * @return \FCForms\FormElement\ElementPrototype
     */
    abstract public function getElement();
    
    public function testFirst()
    {
        $element = $this->getElement();
    }
}
