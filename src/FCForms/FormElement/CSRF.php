<?php

namespace FCForms\FormElement;

use FCForms\FCFormsException;
use FCForms\Form\Form;
use FCForms\Form\DataStore;

class CSRF extends AbstractElementPrototype
{
    /**
     * The form this element is attached to.
     * @var Form
     */
    protected $form;

    public function __construct(Form $form, DataStore $dataStore)
    {
        $this->form = $form;
        $this->dataStore = $dataStore;
    }
    
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

    public function prepareToRender(Element $elementInstance)
    {
        $elementInstance->setCurrentValue(uniqid());
        //Showing a CSRF element, creates the value
        $sessionName = $this->getSessionKeyNameForCSRF($elementInstance);
        $this->dataStore->setValue($sessionName, $elementInstance->getCurrentValue());
    }

    /**
     * Get the session name that stores the CSRF value in.
     * @param Element $elementInstance
     * @throws \Exception
     * @return string
     */
    public function getSessionKeyNameForCSRF(Element $elementInstance)
    {
        if ($elementInstance->getCurrentValue() == null) {
            throw new \Exception("CSRF id not initialized.");
        }
        
        return get_class($this->form)."_csrf_".$elementInstance->getCurrentValue();
    }

    /**
     * Get the value to compare the current value against.
     * @param Element $elementInstance
     * @throws FCFormsException
     * @throws \Exception
     * @return mixed
     */
    public function getValidationValue(Element $elementInstance)
    {
        $sessionName = $this->getSessionKeyNameForCSRF($elementInstance);
        $validationValue = $this->dataStore->getValue($sessionName, false, true);
        if ($validationValue === false) {
            throw new FCFormsException("Could not read value for validation");
        }

        return $validationValue;
    }
}
