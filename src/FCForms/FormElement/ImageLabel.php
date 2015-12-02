<?php

namespace FCForms\FormElement;

use FCForms\Form\Form;

class ImageLabel extends ElementPrototype
{
    /**
     * @return string
     */
    public function getPrototypeCSSClass()
    {
        return "fc_imagelabel";
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

    /**
     * @return mixed|string
     */
    public function render(Form $form, Element $elementInstance)
    {
        $output = "<div class='row-fluid'>";
        $output .= "<div class='" . $this->getPrototypeCSSClass() . " span12'>";
        $output .= "<img src='".$elementInstance->getCurrentValue()."' />";
        $output .= "</img>";
        $output .= "</div>";
        $output .= "</div>";

        return $output;
    }
}
