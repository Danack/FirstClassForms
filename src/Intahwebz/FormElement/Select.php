<?php


namespace Intahwebz\FormElement;


class Select extends AbstractElement {

    private $optionDescriptionMap = array();

    function init(array $info) {
        //$values
        //$descriptions
        $this->optionDescriptionMap = $info['options'];
    }

    /**
     * @return string
     */
    function getCSSClassName() {
        return 'Select';
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

        $output .= "<div class='$remainingSpan'>";

        $output .= "<select name='" . $this->getFormName() . "'>";
        
        foreach ($this->optionDescriptionMap as $option => $description) {
            $selectedString = '';
            if ($option === $this->getCurrentValue()) {
                $selectedString = "selected='selected'";
            }
            
           $output .= "<option value='".safeText($option)."' $selectedString>".safeText($description)."</option>";
        }

        $output .= "</select>";

        $output .= "</div>";
        $output .= "</div>";

        return $output;
    }

}

