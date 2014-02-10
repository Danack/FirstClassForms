<?php


namespace Intahwebz\FormElement;


class Password extends AbstractElement {
    
    /**
     * @param array $info
     * @return mixed|void
     */
    function init(array $info) {
    }

    /**
     * Password fields do not get stored in session to prevent a possible
     * security hole. Any form containing a password does not do a 'postredirectget'
     * automatically, instead the login handler should trigger one, after attempting to
     * validated the password.
     * @return array
     */
    function serialize() {
        return array();
    }
    
    
    /**
     * @return string
     */
    function getCSSClassName() {
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

        
        //$output .= sprintf("<input type='hidden' name='%s' value='%d' />", $this->getIsHashedFormName(), $this->isHashed);
        $output .= "<input type='password' name='".$this->getFormName()."' size='80' value='" . htmlentities($this->getCurrentValue()) . "' placeholder='Password' style='width: 100%;' />";

        $output .= "</div>";
        $output .= "</div>";

        return $output;
    }


}

