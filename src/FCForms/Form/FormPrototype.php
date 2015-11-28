<?php

namespace FCForms\Form;

use FCForms\FormElement\Element;
use FCForms\FormElement\PrototypeCollection;

class FormPrototype
{
    /**
     * If there is an error in the form, this message is displayed at the start of the form.
     * @var string
     */
    public $errorMessage;

    /**
     * The CSS class name for the outer form element
     * @var
     */
    public $class;

    /** @var \FCForms\FormElement\PrototypeCollection|\FCForms\FormElement\AbstractElementPrototype[] */
    public $rowPrototypes = array();

    /** @var \FCForms\FormElement\PrototypeCollection|\FCForms\FormElement\AbstractElementPrototype[] */
    public $startPrototypes = array();

    /** @var \FCForms\FormElement\PrototypeCollection|\FCForms\FormElement\AbstractElementPrototype[] */
    public $endPrototypes = array();

    public function __construct(
        $cssClass,
        $errorMessage,
        $startPrototypes,
        $rowPrototypes,
        $endPrototypes
    ) {
        $this->cssClass = $cssClass;
        $this->errorMessage = $errorMessage;
        $this->startPrototypes = new PrototypeCollection($startPrototypes);
        $this->rowPrototypes = new PrototypeCollection($rowPrototypes);
        $this->endPrototypes = new PrototypeCollection($endPrototypes);
    }
    
    /**
     * @return array|bool
     */
    public function areAllElementsStoreable()
    {
        $isStoreable = true;
        
        foreach ($this->startPrototypes as $element) {
            $isStoreable &= $element->canBeStored();
        }
        
        foreach ($this->rowPrototypes as $element) {
            $isStoreable &= $element->canBeStored();
        }
        
        foreach ($this->endPrototypes as $element) {
            $isStoreable &= $element->canBeStored();
        }
        
        return $isStoreable;
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
     * @param $prototypes \FCForms\FormElement\AbstractElementPrototype[]
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
     * @param $data
     * @return bool
     */
    public function createElementsFromData(Form $form, array $data)
    {
        $startElements = [];
        $rowElementsArray = [];
        $endElements = [];

        foreach ($this->startPrototypes as $prototype) {
            $element = Element::fromData(
                $prototype,
                'start',
                $data
            );
            $startElements[] = $element;
        }

        if (array_key_exists('rows', $data) == true) {
            foreach ($data['rows'] as $rowID => $rowData) {
                $rowID = trim($rowID);
                foreach ($this->endPrototypes as $prototype) {
                    //TODO - check it's set
                    $rowData = $data[$rowID];
                    $element = Element::fromData(
                        $prototype,
                        'end',
                        $rowData
                    );
                    $rowElementsArray[$rowID][] = $element;
                }
            }
        }


        foreach ($this->endPrototypes as $prototype) {
            $element = Element::fromData(
                $prototype,
                'end',
                $data
            );
            $endElements[] = $element;
        }

        if (array_key_exists('forceError', $data) == true) {
            $this->forceError = $data['forceError'];
        }
        
        $form->setElements(
            $startElements,
            $rowElementsArray,
            $endElements
        );

        return true;
    }
}
