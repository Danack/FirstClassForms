<?php

namespace FCForms\FormElement;

class Title extends ElementPrototype
{

    /**
     * @return string
     */
    public function getCSSClassName()
    {
        return "Title";
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
