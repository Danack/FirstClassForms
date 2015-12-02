<?php

namespace FCForms\FormElement;

class Title extends ElementPrototype
{

    /**
     * @return string
     */
    public function getPrototypeCSSClass()
    {
        return "fc_title";
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
