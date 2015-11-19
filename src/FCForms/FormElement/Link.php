<?php


namespace FCForms\FormElement;

class Link extends AbstractElement
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
    public function render()
    {
        $output = "<div class='row-fluid'>";
        $output .= "<div class='" . $this->getStyleName() . " span12'>";
        //$output .= $this->getCurrentValue();
        $output .= "This is meant to be a link";
        $output .= "</div>";
        $output .= "</div>";

        return $output;
    }
}
