<?php

namespace FCFormsTest;

use FCForms\FormElement\CheckBox;

class CheckBoxTest extends AbstractElementTest
{

    /**
     * @return \FCForms\FormElement\ElementPrototype
     */
    public function getElement()
    {
        return new CheckBox();
    }
}
