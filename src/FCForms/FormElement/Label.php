<?php

namespace FCForms\FormElement;

class Label extends AbstractElement
{
    /**
     * @return string
     */
    public function getCSSClassName()
    {
        return "Label";
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
        $output .= $this->getCurrentValue();
        $output .= "</div>";
        $output .= "</div>";

        return $output;
    }
}
