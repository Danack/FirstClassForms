<?php


namespace FCForms\FormElement;

use FCForms\Form\Form;

class Select extends ElementPrototype
{
    private $optionDescriptionMap = array();

    private $multipleAllowed = false;


    public function init(array $info)
    {
        //$values
        //$descriptions
        $this->optionDescriptionMap = $info['options'];
    }

    public function getOptionDescriptionMap()
    {
        return $this->optionDescriptionMap;
    }

    public function multipleAllowed()
    {
        return $this->multipleAllowed;
    }
    
    /**
     * @return string
     */
    public function getPrototypeCSSClass()
    {
        return 'fc_select';
    }
}
