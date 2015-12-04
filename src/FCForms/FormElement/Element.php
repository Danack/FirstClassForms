<?php


namespace FCForms\FormElement;

use Room11\HTTP\VariableMap;

class Element
{
    /**
     * @var string[]
     */
    public $errorMessages = array();

    /**
     * @var
     */
    protected $currentValue;

    /**
     * @var
     */
    protected $rowID;

    /**
     * @var ElementPrototype
     */
    protected $prototype;

    public function __construct(
        ElementPrototype $elementDefinition,
        $rowID
    ) {
        $this->prototype = $elementDefinition;
        $this->rowID = $rowID;
    }

    /**
     * @return ElementPrototype
     */
    public function getPrototype()
    {
        return $this->prototype;
    }

    /**
     * @param ElementPrototype $prototype
     * @param $rowID
     * @param $data
     * @return Element
     */
    public static function fromData(
        ElementPrototype $prototype,
        $rowID,
        $data
    ) {
        $element = new self($prototype, $rowID);
        if ($prototype->getDefaultValue() !== null) {
            $element->setCurrentValue($prototype->getDefaultValue());
        }
        $element->useData($data);

        return $element;
    }

    /**
     * @return mixed
     */
    public function getRowID()
    {
        return $this->rowID;
    }

    /**
     * @param $value
     */
    public function setCurrentValue($value)
    {
        foreach ($this->prototype->getFilters() as $filter) {
            $value = $filter->filter($value);
        }
        $this->currentValue = $value;
    }
    
        /**
     * @param $serializedData
     */
    public function useData($serializedData)
    {
        if ($this->prototype->getName() != null) {
            
            $formName = $this->prototype->getFormName($this->rowID);
            
            if (array_key_exists($formName, $serializedData) == true) {
                $value = $this->prototype->deserialize($serializedData[$formName]);
                $this->setCurrentValue($value);
            }
        }
    }

    /**
     * @param $dataSource
     */
    public function setValue($dataSource)
    {
        if (array_key_exists($this->prototype->getName(), $dataSource) == true) {
            $this->setCurrentValue($dataSource[$this->prototype->getName()]);
        }
    }

    /**
     *
     */
    public function reset()
    {
        $this->setCurrentValue($this->prototype->getDefaultValue());
    }

    /**
     * @return \string[]
     */
    public function getErrorMessages()
    {
        return $this->errorMessages;
    }

    /**
     * Return the name of the prototype. This mostly shouldn't be used directly,
     * but only in conjunction with the ID of the row.
     * @return string
     */
    public function getName()
    {
        return $this->prototype->getName();
    }
    
    /**
     * @return string
     */
    public function getFormName()
    {
        return $this->prototype->getFormName($this->rowID);
    }
    
        /**
     * @return mixed
     */
    public function getCurrentValue()
    {
        return $this->currentValue;
    }

    /**
     * @return bool
     */
    public function hasData()
    {
        return $this->prototype->hasData();
    }

    /**
     * @return bool
     */
    public function validate()
    {
        $isValid = true;

        foreach ($this->prototype->getValidationRules() as $validationRule) {
            if ($validationRule instanceof ElementValidator) {
                $validationResult = $validationRule->isValidElement($this);
            }
            else {
                $validationResult = $validationRule->isValid($this->currentValue);
            }

            if ($validationResult == false) {
                $isValid = false;
                $this->errorMessages = array_merge($this->errorMessages, $validationRule->getMessages());
            }
        }

        return $isValid;
    }

    /**
     *
     */
    public function prepareToRender()
    {
        $this->prototype->prepareToRender($this);
    }
    
    public function getValidationValue()
    {
        return $this->prototype->getValidationValue($this);
    }
    
    public function hasError()
    {
        //TODO - separate error messages from error state?
        $errorMessages = $this->getErrorMessages();
        if (count($errorMessages)) {
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function serialize()
    {
        return $this->prototype->serialize($this);
    }
}
