<?php

namespace FCFormsTest;

use FCForms\FormElement\Label;

class LabelTest extends AbstractElementTest
{
    /**
     * @return \FCForms\FormElement\AbstractElementPrototype
     */
    public function getElement()
    {
        return new Label();
    }
}
