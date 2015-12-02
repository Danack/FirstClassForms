<?php


namespace FCForms\FormElement;

use FCForms\Form\Form;

class Link extends ElementPrototype
{
    /**
     * @return string
     */
    public function getPrototypeCSSClass()
    {
        return "fc_link";
    }

    /**
     * @param array $info
     * @return mixed|void
     */
    public function init(array $info)
    {
    }

    /**
     * @return mixed|string
     */
    public function render(Form $form, Element $elementInstance)
    {
        $output = "<div class='row-fluid'>";
        $output .= "<div class='".$this->getPrototypeCSSClass()." span12'>";
        //$output .= $this->getCurrentValue();
        $output .= "This is meant to be a link";
        $output .= "</div>";
        $output .= "</div>";

        return $output;
    }
}
