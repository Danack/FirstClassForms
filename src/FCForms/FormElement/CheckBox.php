<?php

namespace FCForms\FormElement;

use FCForms\Form\Form;

class CheckBox extends ElementPrototype
{
    public function init(array $info)
    {
    }

    /**
     * @return string
     */
    public function getCSSClassName()
    {
        return 'CheckBox';
    }
}
