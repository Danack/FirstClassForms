<?php

namespace FCForms\FormElement;

use FCForms\Form\Form;

class Password extends AbstractElementPrototype
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
    public function render(Form $form)
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
            $labelSpan = "span" . $form->getLabelSpan();
            $remainingSpan = "span" . (12 - $form->getLabelSpan());
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
    
    public function canBeStored()
    {
        return false;
    }
}
