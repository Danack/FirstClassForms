<?php

namespace FCForms\FormElement;

use FCForms\Form\Form;

class Hidden extends ElementPrototype
{
    /**
     * @return string
     */
    public function getPrototypeCSSClass()
    {
        return 'fc_hidden';
    }

    /**
     * @param array $info
     * @return mixed|void
     */
    public function init(array $info)
    {
    }
}
