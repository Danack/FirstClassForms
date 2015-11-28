<?php


namespace FCForms\FormElement;

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
     * @var AbstractElementPrototype
     */
    protected $prototype;

    public function __construct(
        AbstractElementPrototype $elementDefinition,
        $rowID
    ) {
        $this->prototype = $elementDefinition;
        $this->rowID = $rowID;
    }

    /**
     * @return AbstractElementPrototype
     */
    public function getPrototype()
    {
        return $this->prototype;
    }

    /**
     * @param AbstractElementPrototype $prototype
     * @param $rowID
     * @param $data
     * @return Element
     */
    public static function fromData(
        AbstractElementPrototype $prototype,
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
}
