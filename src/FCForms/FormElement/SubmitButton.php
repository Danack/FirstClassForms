<?php

namespace FCForms\FormElement;

class SubmitButton extends AbstractElement
{
    public $text;

    /**
     * @return string
     */
    public function getCSSClassName()
    {
        return "SubmitButton";
    }

    /**
     * @return mixed|string
     */
    public function render()
    {
        $output = "";

        $output .= "<div class='row-fluid'>";

        $offsetClass = 'span' . ($this->form->getLabelSpan());
        $spanClass = 'span' . (12 - $this->form->getLabelSpan());

        $output .= "<div class='$offsetClass'>";
        $output .= "&nbsp;";
        $output .= "</div>";
        $output .= "<div class='$spanClass'>";
        $output .= "<input type='submit' name='".$this->getFormName()."' value='" . $this->text . "' />";

        $output .= "</div>";
        $output .= "</div>";

        return $output;
    }

    /**
     * @param array $info
     * @return mixed|void
     */
    public function init(array $info)
    {
        $this->text = $info['text'];
        $this->name = 'submitButton';
    }
}
