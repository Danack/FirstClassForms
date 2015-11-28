<?php

namespace FCForms\FormElement;

use FCForms\Form\Form;

class Hidden extends AbstractElementPrototype
{
    /**
     * @return string
     */
    public function getCSSClassName()
    {
        return 'Hidden';
    }

    /**
     * @param array $info
     * @return mixed|void
     */
    public function init(array $info)
    {
    }
}
