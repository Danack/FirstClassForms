<?php


namespace FCForms\FormElement;

use FCForms\Form\Form;

class Select extends ElementPrototype
{
    private $optionDescriptionMap = array();

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

    /**
     * @return string
     */
    public function getPrototypeCSSClass()
    {
        return 'fc_select';
    }
}
