<?php


namespace Intahwebz\FormElement;


class CheckBox extends AbstractElement {

    function init(array $info) {
    }

    /**
     * @param $value
     */
    public function setCurrentValue($value) {
        if ($value == 'false') {
            $value = false; //False values from Javascript is passed as string 'false'.
        }

        $this->currentValue = (bool)$value;
    }

    /**
     * @return string
     */
    function getClassName() {
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
        if ($this->currentValue == true) {
            $checked = "checked='checked'";
        }

        $output .= "<div class='$remainingSpan'>";
        $output .= "<input type='checkbox' name='" . $this->getFormName() . "' value='1' $checked />";

        $output .= "</div>";
        $output .= "</div>";

        return $output;
    }

    /**
     * @return bool|mixed
     */
    function getCurrentValue() {
        if ($this->currentValue) {
            return true;
        }

        return false;
    }
}

