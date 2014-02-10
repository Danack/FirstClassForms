<?php


namespace Intahwebz\FormElement;


class CSRF extends AbstractElement {

    /**
     * @return string
     */
    function getCSSClassName() {
        return 'CSRF';
    }
    
    /**
     * @param array $info
     * @return void
     */
    function init(array $info) {
        if (array_key_exists('name', $info) == true) {
            $this->name = $info['name'];
        }
        else {
            $this->name = 'csrf';
        }
    }

    /**
     * Renders the form element
     * @return string
     */
    function render() {
        $output = "";
        $this->setCurrentValue(uniqid());
        //Showing a CSRF element, creates the value
        $sessionName = $this->getSessionName();
        $session = $this->form->getSession();
        $session->setSessionVariable($sessionName, $this->getCurrentValue());

        if (count($this->errorMessages) > 0) {
            $output .= "<div class='errorMessage'>";

            foreach ($this->errorMessages as $errorMessage) {
                $output .= $errorMessage;
            }
            $output .= "</div>";
        }

        $output .= "<input type='hidden' name='" . $this->getFormName() . "' value='" . $this->getCurrentValue() . "' />";

        return $output;
    }

    /**
     * Get the session name that stores the CSRF value in.
     * @return string
     */
    public function getSessionName() {
        return get_class($this->form)."_csrf";
    }

    /**
     * Get the value to compare the current value against.
     * @return mixed
     */
    public function getValidationValue() {
        $sessionName = $this->getSessionName($this->getCurrentValue());
        $session = $this->form->getSession();
        $validationValue = $session->getSessionVariable($sessionName, false, true);

        return $validationValue;
    }
}
