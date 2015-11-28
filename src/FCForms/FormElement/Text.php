<?php


namespace FCForms\FormElement;

use FCForms\Form\Form;

class Text extends AbstractElementPrototype
{
    /**
     * @param array $info
     * @return mixed|void
     */
    public function init(array $info)
    {
    }

    /**
     * @return string
     */
    public function getCSSClassName()
    {
        return "InputText";
    }
}
