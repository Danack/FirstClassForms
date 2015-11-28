<?php

namespace FCForms\FormElement;

use FCForms\Form\Form;

class CheckBox extends AbstractElementPrototype
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
