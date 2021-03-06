<?php

namespace FCForms\FormElement;

use FCForms\FCFormsException;
use FCForms\DataMissingException;
use FCForms\Form\Form;
use FCForms\DataStore;
use Room11\HTTP\VariableMap;

class CSRF extends ElementPrototype
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
    public function getPrototypeCSSClass()
    {
        return 'fc_csrf';
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
     * @param Element $elementInstance
     * @throws \Exception
     */
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
            throw new DataMissingException("Could not read value for validation");
        }

        return $validationValue;
    }
}
