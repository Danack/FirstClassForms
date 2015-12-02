<?php

namespace FCForms\FormElement;

use FCForms\Form\Form;

class Label extends ElementPrototype
{
    /**
     * @return string
     */
    public function getPrototypeCSSClass()
    {
        return "fc_label";
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
}
