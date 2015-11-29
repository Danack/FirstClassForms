<?php

namespace FCFormsTest;

use FCForms\FormElement\Label;

class LabelTest extends AbstractElementTest
{
    /**
     * @return \FCForms\FormElement\ElementPrototype
     */
    public function getElement()
    {
        return new Label();
    }
}
