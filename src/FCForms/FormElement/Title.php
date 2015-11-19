<?php


namespace FCForms\FormElement;

class Title extends AbstractElement
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

    /**
     * @return mixed|string
     */
    public function render()
    {
        $output = "<div class='row-fluid'>";
        $output .= "<legend class='span12'>";
        $output .= $this->getCurrentValue();
        $output .= "</legend>";
        $output .= "</div>";

        return $output;
    }
}
