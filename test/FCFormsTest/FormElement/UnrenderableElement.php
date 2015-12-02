<?php

namespace FCFormsTest\FormElement;

use FCForms\FormElement\ElementPrototype;

class UnrenderableElement extends ElementPrototype
{

    /**
     * @return string
     */
    public function getPrototypeCSSClass()
    {
        return "Unrenderable";
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
