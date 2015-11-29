<?php

namespace FCForms\FormElement;

use FCForms\Form\Form;

class SubmitButton extends ElementPrototype
{
    public $text;

    /**
     * @return string
     */
    public function getCSSClassName()
    {
        return "SubmitButton";
    }

    /**
     * @param array $info
     * @return mixed|void
     */
    public function init(array $info)
    {
        $this->text = $info['text'];
        $this->name = 'submitButton';
    }
}
