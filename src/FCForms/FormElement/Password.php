<?php

namespace FCForms\FormElement;

class Password extends AbstractElement
{
    /**
     * @param array $info
     * @return mixed|void
     */
    public function init(array $info)
    {
    }

    /**
     * Password fields do not get stored in session to prevent a possible
     * security hole. Any form containing a password does not do a 'postredirectget'
     * automatically, instead the login handler should trigger one, after attempting to
     * validated the password.
     * @return array
     */
    public function serialize()
    {
        return array();
    }
    
    
    /**
     * @return string
     */
    public function getCSSClassName()
    {
        return "InputText";
    }

    /**
     * @return mixed|string
     */
    public function render()
    {
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

        $output .= sprintf(
            "<input type='password' name='%s' size='80' value='%s' placeholder='Password' style='width: 100%;' />",
            $this->getFormName(),
            htmlentities($this->getCurrentValue())
        );

        $output .= "</div>";
        $output .= "</div>";

        return $output;
    }
    
    public function isStoreable()
    {
        return false;
    }
}
