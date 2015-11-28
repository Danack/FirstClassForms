<?php

namespace FCForms\FormElement;

use FCForms\Form\Form;

class Label extends AbstractElementPrototype
{
    /**
     * @return string
     */
    public function getCSSClassName()
    {
        return "Label";
    }

    /**
     * @param array $info
     * @return mixed|void
     */
    public function init(array $info)
    {
    }

    public function hasData()
    {
        return false;
    }
    
    /**
     * @return mixed
     */
    public function render(Form $form, Element $elementInstance)
    {
        return "This is a label";
    }
}
