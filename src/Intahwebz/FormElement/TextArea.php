<?php


namespace Intahwebz\FormElement;


class TextArea extends AbstractElement {

    
    private $rows = 8;
    
    private $cols = 80;

    /**
     * @param array $info
     * @return mixed|void
     */
    function init(array $info) {
        
        if (array_key_exists('rows', $info) == true) {
            $this->rows = intval($info['rows']);
        }
        if (array_key_exists('cols', $info) == true) {
            $this->cols = intval($info['cols']);
        }
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
        $output .= "<textarea type='text' name='".$this->getFormName(). "' placeholder='Name' rows='".$this->rows."' cols='".$this->cols."' />";

        $output .= htmlentities($this->getCurrentValue());

        $output .= "</textarea>";

        $output .= "</div>";
        $output .= "</div>";

        return $output;
    }
}

