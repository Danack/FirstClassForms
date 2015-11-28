<?php

namespace FCForms\FormElement;

use FCForms\FormDefinitionException;

abstract class AbstractElementPrototype
{
    /** @var  string */
    protected $name;

    /**
     * @var null
     */
    protected $placeHolder = null;

    /** @var  \Zend\Validator\ValidatorInterface[] */
    protected $validationRules = array();

    /** @var \Zend\Filter\AbstractFilter[] */
    protected $filters = array();

    protected $defaultValue = null;
    
    /**
     * @var string
     */
    public $label = null;

    /**
     * Use to generate the class that is applied to the element, to allow
     * it to be styled specifically.
     * @return mixed
     */
    abstract public function getCSSClassName();
    
    /**
     * @param array $info
     * @return mixed
     */
    abstract public function init(array $info);

    /**
     * Most form elements validate on the current value. However some, like CSRF, will validate
     * against the value stored in the session.
     * @return mixed
     */
    public function getValidationValue(Element $elementInstance)
    {
        return $elementInstance->getCurrentValue();
    }

    /**
     * @return \Zend\Validator\ValidatorInterface[]
     */
    public function getValidationRules()
    {
        return $this->validationRules;
    }

    /**
     * @return \Zend\Filter\AbstractFilter[]
     */
    public function getFilters()
    {
        return $this->filters;
    }


    /**
     * The default value for this type of element. Null indicates no value
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return null
     */
    public function getPlaceHolder()
    {
        return $this->placeHolder;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param $rowID
     * @return string
     */
    public function getFormName($rowID)
    {
        if ($rowID == 'start' ||
            $rowID == 'end') {
            return $this->getName();
        }
        
        return $rowID."_".$this->getName();
    }

    /**
     * @param $formElement
     */
    public function initCommon($formElement)
    {
        if (array_key_exists('name', $formElement) == true) {
            $this->name = $formElement['name'];
        }
        else if ($this->hasData() == true) {
            throw new FormDefinitionException("Element lacks a name: ".var_export($formElement, true));
        }

        if (array_key_exists('label', $formElement) == true) {
            $this->label = $formElement['label'];
            $this->placeHolder = $this->label;
        }

        if (array_key_exists('placeHolder', $formElement) == true) {
            $this->placeHolder = $formElement['placeHolder'];
        }

        // @TODO - naming things is hard
        if (array_key_exists('value', $formElement) == true) {
            $this->defaultValue = $formElement['value'];
        }
        if (array_key_exists('default', $formElement) == true) {
            $this->defaultValue = $formElement['default'];
        }

        if (array_key_exists('filter', $formElement) == true) {
            $this->addFilters($formElement['filter']);
        }

        if (array_key_exists('validation', $formElement) == true) {
            $this->addValidationRules($formElement['validation']);
        }
    }

    /**
     * @param $filterArray
     */
    public function addFilters($filterArray)
    {
        foreach ($filterArray as $filterClassname => $options) {
            if (is_object($options) == true) {
                $this->filters[] = $options;
            }
            else {
                $validator = new $filterClassname($options);
                $this->filters[] = $validator;
            }
        }
    }

    /**
     * @param $validationInfoArray
     */
    public function addValidationRules($validationInfoArray)
    {
        foreach ($validationInfoArray as $className => $options) {
            if (is_object($options) == true) {
                $this->validationRules[] = $options;
            }
            else {
                $validator = new $className($options);
                $this->validationRules[] = $validator;
            }
        }
    }

    /**
     * @return array
     */
    public function serialize(Element $elementInstance)
    {
        return array($this->name => $elementInstance->getCurrentValue());
    }

    /**
     * @param $string
     * @return mixed
     */
    public function deserialize($string)
    {
        return $string;
    }

    /**
     * @return bool
     */
    public function hasData()
    {
        return true;
    }

    /**
     * Whether the value can be safely stored in a session or other data storage
     * Most things can be stored, but passwords and other secrets shouldn't be stored.
     * @return bool
     */
    public function canBeStored()
    {
        return true;
    }

    /**
     * Some elements e.g. CSRF need to perform an action between a form being
     * validated and it being displayed
     * @param Element $elementInstance
     */
    public function prepareToRender(Element $elementInstance)
    {

    }
}
