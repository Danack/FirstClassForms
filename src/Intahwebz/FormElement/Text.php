<?php


namespace Intahwebz\FormElement;


class Text extends AbstractElement {

    /**
     * @param array $info
     * @return mixed|void
     */
    function init(array $info) {
    }

    /**
     * @return string
     */
    function getClassName() {
        return "InputText";
    }

    /**
     * @return mixed|string
     */
    function render() {

        $output = "";
        //$output .= "<div class='".$this->getStyleName()."'>";
        if (count($this->errorMessages) > 0) {
            $output .= "<div class='row-fluid'>";
            $output .= "<div class='errorMessage span12'>";
            foreach ($this->errorMessages as $errorMessage) {
                $output .= $errorMessage;
            }
            $output .= "</div>";
            $output .= "</div>";
        }

        $output .= "<div class='row-fluid'>";
        $remainingSpan = 'span12';

        if ($this->label !== null) {
            $labelSpan = "span" . $this->form->getLabelSpan();
            $remainingSpan = "span" . (12 - $this->form->getLabelSpan());
            $output .= "<label class='$labelSpan' for='" . $this->getFormName() . "'>" . $this->label . "</label>";
        }

        $output .= "<div class='$remainingSpan'>";
        $output .= "<input type='text' name='" . $this->getFormName() . "' size='80' value='" . htmlentities($this->currentValue) . "' placeholder='Name' style='width: 100%;' />";

        $output .= "</div>";
        $output .= "</div>";

        return $output;
    }
}
