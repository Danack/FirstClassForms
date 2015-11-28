<?php

namespace FCFormsTest;

use FCForms\FormElement\CheckBox;

class CheckBoxTest extends AbstractElementTest
{

    /**
     * @return \FCForms\FormElement\AbstractElementPrototype
     */
    public function getElement()
    {
        return new CheckBox();
    }
}
