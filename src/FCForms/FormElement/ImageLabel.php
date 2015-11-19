<?php

namespace FCForms\FormElement;

class ImageLabel extends AbstractElement
{
    /**
     * @return string
     */
    public function getCSSClassName()
    {
        return "ImageLabel";
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
    public function render()
    {
        $output = "<div class='row-fluid'>";
        $output .= "<div class='" . $this->getStyleName() . " span12'>";
        $output .= "<img src='".$this->getCurrentValue()."' />";
        $output .= "</img>";
        $output .= "</div>";
        $output .= "</div>";

        return $output;
    }
}
