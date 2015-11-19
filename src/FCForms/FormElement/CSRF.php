<?php

namespace FCForms\FormElement;

class CSRF extends AbstractElement
{
    /**
     * @return string
     */
    public function getCSSClassName()
    {
        return 'CSRF';
    }

    /**
     * @param array $info
     * @return void
     */
    public function init(array $info)
    {
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
    public function render()
    {
        $output = "";
        $this->setCurrentValue(uniqid());
        //Showing a CSRF element, creates the value
        $sessionName = $this->getSessionKeyNameForCSRF();
        $dataStore = $this->form->getDataStore();
        $dataStore->storeData($sessionName, $this->getCurrentValue());

        if (count($this->errorMessages) > 0) {
            $output .= "<div class='errorMessage'>";

            foreach ($this->errorMessages as $errorMessage) {
                $output .= $errorMessage;
            }
            $output .= "</div>";
        }

        $output .= sprintf(
            "<input type='hidden' name='%s' value='%s' />",
            $this->getFormName(),
            $this->getCurrentValue()
        );

        return $output;
    }

    /**
     * Get the session name that stores the CSRF value in.
     * @return string
     */
    public function getSessionKeyNameForCSRF()
    {
        return get_class($this->form)."_csrf";
    }

    /**
     * Get the value to compare the current value against.
     * @return mixed
     */
    public function getValidationValue()
    {
        $sessionName = $this->getSessionKeyNameForCSRF($this->getCurrentValue());
        $dataStore = $this->form->getDataStore();
        $validationValue = $dataStore->getData($sessionName, false, true);

        return $validationValue;
    }
}
