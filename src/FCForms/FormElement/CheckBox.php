<?php

namespace FCForms\FormElement;

class CheckBox extends ElementPrototype
{
    public function init(array $info)
    {
    }

    /**
     * @return string
     */
    public function getPrototypeCSSClass()
    {
        return 'fc_checkbox';
    }
}
