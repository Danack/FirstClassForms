<?php

namespace FCForms\FormElement;

class Title extends AbstractElementPrototype
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
    
//    /**
//     * @return mixed|string
//     */
//    public function render(Form $form, Element $elementInstance)
//    {
//        $output = "<div class='row-fluid'>";
//        $output .= "<legend class='span12'>";
//        $output .= $elementInstance->getCurrentValue();
//        $output .= "</legend>";
//        $output .= "</div>";
//
//        return $output;
//    }
}
