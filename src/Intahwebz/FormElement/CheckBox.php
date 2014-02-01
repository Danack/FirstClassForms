<?php


namespace Intahwebz\FormElement;


class CheckBox extends AbstractElement {

    function init(array $info) {
    }

    /**
     * @return string
     */
    function getCSSClassName() {
        return 'CheckBox';
    }

    /**
     * @return mixed|string
     */
    function render() {
        $output = "<div class='row-fluid'>";
        $labelSpan = "span" . $this->form->getLabelSpan();

        $remainingSpan = "span" . (12 - $this->form->getLabelSpan());

        if ($this->label != null) {
            $output .= "<label class='$labelSpan' for='" . $this->getFormName() . "'>" . $this->label . "</label>";
        }

        $checked = '';
        if ($this->getCurrentValue() == true) {
            $checked = "checked='checked'";
        }

        $output .= "<div class='$remainingSpan'>";
        $output .= "<input type='checkbox' name='" . $this->getFormName() . "' value='1' $checked />";

        $output .= "</div>";
        $output .= "</div>";

        return $output;
    }
}

