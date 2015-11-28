<?php


namespace FCForms\FormElement;

use FCForms\Form\Form;

class Link extends AbstractElementPrototype
{
    /**
     * @return string
     */
    public function getCSSClassName()
    {
        return "Link";
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
        $output .= "<div class='".$this->getCSSClassName()." span12'>";
        //$output .= $this->getCurrentValue();
        $output .= "This is meant to be a link";
        $output .= "</div>";
        $output .= "</div>";

        return $output;
    }
}
