<?php

namespace FCForms\Form;

use FCForms\FormElement\Element;
use FCForms\FormElement\PrototypeCollection;
use FCForms\FCFormsException;
use Room11\HTTP\VariableMap;

class FormPrototype
{
    /**
     * If there is an error in the form, this message is displayed at the start of the form.
     * @var string
     */
    public $defaultErrorMessage = "Form had errors.";

    /**
     * The CSS class name for the outer form element
     * @var
     */
    public $class;

    /** @var \FCForms\FormElement\PrototypeCollection|\FCForms\FormElement\ElementPrototype[] */
    public $rowPrototypes = array();

    /** @var \FCForms\FormElement\PrototypeCollection|\FCForms\FormElement\ElementPrototype[] */
    public $startPrototypes = array();

    /** @var \FCForms\FormElement\PrototypeCollection|\FCForms\FormElement\ElementPrototype[] */
    public $endPrototypes = array();

    public function __construct(
        $cssClass,
        $errorMessage,
        $startPrototypes,
        $rowPrototypes,
        $endPrototypes
    ) {
        $this->cssClass = $cssClass;
        $this->defaultErrorMessage = $errorMessage;
        $this->startPrototypes = new PrototypeCollection($startPrototypes);
        $this->rowPrototypes = new PrototypeCollection($rowPrototypes);
        $this->endPrototypes = new PrototypeCollection($endPrototypes);
    }

    /**
     * @return bool
     */
    public function canAllElementsBeStored()
    {
        $canBeStored = true;

        foreach ($this->startPrototypes as $element) {
            $canBeStored = $canBeStored && $element->canBeStored();
        }

        foreach ($this->rowPrototypes as $element) {
            $canBeStored = $canBeStored && $element->canBeStored();
        }

        foreach ($this->endPrototypes as $element) {
            $canBeStored = $canBeStored && $element->canBeStored();
        }

        return $canBeStored;
    }

    public function getStartDataNames()
    {
        return $this->getDataNames($this->startPrototypes);
    }
    
    public function getRowDataNames()
    {
        return $this->getDataNames($this->rowPrototypes);
    }
    
    public function getEndDataNames()
    {
        return $this->getDataNames($this->endPrototypes);
    }
    
    /**
     * @param $prototypes \FCForms\FormElement\ElementPrototype[]
     * @return array
     */
    public function getDataNames($prototypes)
    {
        $names = [];
        foreach ($prototypes as $prototype) {
            $name = $prototype->getName();
            if ($name != null) {
                $names[] = $name;
            }
        }
        return $names;
    }

    /**
     * @param \FCForms\FormElement\ElementPrototype[] $prototypes
     */
    protected function createElementsForRow(PrototypeCollection $prototypes, array $data, $rowID)
    {
        $elements = [];
        foreach ($prototypes as $prototype) {
            $element = Element::fromData(
                $prototype,
                $rowID,
                $data
            );
            $elements[] = $element;
        }

        return $elements;
    }
    
    
    
    /**
     * @param $data
     * @return bool
     */
    public function createElementsFromData(Form $form, array $data, array $rowDataArray = [])
    {
        $rowElementsArray = [];

        $startElements = $this->createElementsForRow(
            $this->startPrototypes,
            $data,
            'start'
        );

        $endElements = $this->createElementsForRow(
            $this->endPrototypes,
            $data,
            'end'
        );

        foreach ($rowDataArray as $rowID => $rowData) {
            $rowID = trim($rowID);
            $rowElementsArray[$rowID] = $this->createElementsForRow(
                $this->rowPrototypes,
                $data,
                $rowID
            );
        }
        
        $form->setElements(
            $startElements,
            $rowElementsArray,
            $endElements
        );

        return true;
    }

    /**
     * @param VariableMap $variableMap
     * @param \FCForms\FormElement\ElementPrototype[]|PrototypeCollection $prototypes
     * @param $rowID
     * @return array
     */
    protected function getDataForPrototypes(
        VariableMap $variableMap,
        PrototypeCollection $prototypes,
        $rowID
    ) {
        $data = [];

        foreach ($prototypes as $prototype) {
            if ($prototype->getName() != null) {
                $rowSpecificName = $prototype->getFormName($rowID);
                $value =  $variableMap->getVariable($rowSpecificName, null);
                $data[$rowSpecificName] = $value;
            }
        }

        return $data;
    }


    /**
     * @param Form $form
     * @param VariableMap $variableMap
     * @return bool
     */
    public function createElementsFromVariableMap(Form $form, VariableMap $variableMap)
    {
        $startData = $this->getDataForPrototypes($variableMap, $this->startPrototypes, 'start');
        $endData = $this->getDataForPrototypes($variableMap, $this->endPrototypes, 'end');
        $data = array_merge($startData, $endData);
        
        $rowIDs = $variableMap->getVariable("rowIDs", false);

        if ($rowIDs == false) {
            return $this->createElementsFromData($form, $data);
        }

        $rowIDs = explode(',', $rowIDs);
        foreach ($rowIDs as $rowID) {
            $rowID = trim($rowID);
            $data[$rowID] = $this->getDataForPrototypes($variableMap, $this->rowPrototypes, $rowID);
        }
        
        return $this->createElementsFromData($form, $data);
    }

    /**
     * @param $elementName
     * @return \FCForms\FormElement\ElementPrototype
     * @throws FCFormsException
     */
    public function getPrototypeByName($elementName, $topLevelElementsOnly = true)
    {
        foreach ($this->startPrototypes as $prototype) {
            if ($prototype->getName() == $elementName) {
                return $prototype;
            }
        }
        foreach ($this->endPrototypes as $prototype) {
            if ($prototype->getName() == $elementName) {
                return $prototype;
            }
        }
        if ($topLevelElementsOnly == false) {
            foreach ($this->rowPrototypes as $prototype) {
                if ($prototype->getName() == $elementName) {
                    return $prototype;
                }
            }
        }

        throw new FCFormsException("Form does not have an element named '$elementName'");
    }
}
