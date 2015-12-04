<?php

namespace FCForms\FormElement;

class RadioButton extends ElementPrototype
{
    private $optionDescriptionMap = array();

    private $multipleAllowed = false;

    /**
     * @return string
     */
    public function getPrototypeCSSClass()
    {
        return "fc_radiobutton";
    }

    /**
     * @param array $info
     * @return mixed|void
     */
    public function init(array $info)
    {
        $this->optionDescriptionMap = $info['options'];
    }

    public function hasData()
    {
        return true;
    }
    
    
    public function getOptionDescriptionMap()
    {
        return $this->optionDescriptionMap;
    }

    public function multipleAllowed()
    {
        return $this->multipleAllowed;
    }
}
