<?php

namespace FCForms\FormElement;

use FCForms\Form\Form;
use FCForms\SafeAccess;

class FormElementCollection
{
    use SafeAccess;

    /** @var  int The row identifier - which is either 'add' for a new item or the primary ID (or equivalent). */
    public $id;

    /**
     * @var \FCForms\Form\Form The form this element is used in.
     */
    private $form;

    /** @var AbstractElement[] */
    public $elements = array();

    protected $className = "collection";

    /**
     * @return string
     */
    public function getStyleName()
    {
        return $this->form->getClassName() . "_" . $this->className;
    }

    /**
     * @return int
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function serialize()
    {
        $result = array();

        foreach ($this->elements as $element) {
            $result = array_merge($result, $element->serialize());
        }

        return $result;
    }

    /**
     * @param Form $form
     * @param $rowID
     * @param $rowElements AbstractElement[]
     * @throws \Exception
     */
    public function __construct(Form $form, $rowID, $rowElements)
    {
        $this->form = $form;
        $this->id = $rowID;

        foreach ($rowElements as $rowElement) {
            if (is_object($rowElement) == false) {
                throw new \Exception("row element is not an object.");
            }

            $newElement = clone $rowElement;
            $newElement->setID($rowID);
            $this->elements[] = $newElement;
        }
    }
    
    public function getErrorMessages()
    {
        $errorMessages = [];
        foreach ($this->elements as $element) {
            $errorMessages[] = $element->getErrorMessages();
        }
        
        return $errorMessages;
    }

    public function isStoreable()
    {
        $storeable = true;
        foreach ($this->elements as $element) {
            $storeable &= $element->isStoreable();
        }
        
        return $storeable;
    }
    
    
    /**
     * @return bool
     */
    public function validate()
    {
        $isValid = true;
        foreach ($this->elements as $element) {
            $isValid = ($isValid && $element->validate());
        }

        return $isValid;
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function getValue($name)
    {
        foreach ($this->elements as $element) {
            if ($element->getName() == $name) {
                return $element->getCurrentValue();
            }
        }

        return null;
    }

    public function getAllValues()
    {
        $data = [];
        foreach ($this->elements as $element) {
            $data[$element->getName()] = $element->getCurrentValue();
        }
        
        return $data;
    }
    
    
    /**
     * @param $dataSource
     */
    public function setValues($dataSource)
    {
        foreach ($this->elements as $element) {
            $element->setValue($dataSource);
        }
    }

    /**
     *
     */
    public function useSubmittedValue()
    {
        foreach ($this->elements as $element) {
            $element->useSubmittedValue();
        }
    }

    /**
     * @param $rowData
     */
    public function useData($rowData)
    {
        foreach ($this->elements as $element) {
            $element->useData($rowData);
        }
    }

    /**
     * @return string
     */
    public function render()
    {
        $output = "";

        foreach ($this->elements as $element) {
            $output .= $element->render();
        }

        return $output;
    }
}
