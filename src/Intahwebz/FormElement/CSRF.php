<?php


namespace Intahwebz\FormElement;


class CSRF extends AbstractElement {

    /**
     * TODO PHP5.5
     * @return string
     */
    function getClassName() {
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
        //Showing a CSRF element, creates the value
        $this->setCurrentValue(uniqid()); //mt_rand(1000000, 10000000);
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
        //TOdO replace with get uniqueid based on form-name
        return 'CSRF'; //.$this->currentValue;
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
