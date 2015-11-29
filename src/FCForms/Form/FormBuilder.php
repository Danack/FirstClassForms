<?php


namespace FCForms\Form;

use Auryn\Injector;
use FCForms\FCFormsException;
use FCForms\Form\FormPrototype;

class FormBuilder
{
    private $injector;

    public function __construct(Injector $injector)
    {
        $this->injector = $injector;
    }

    public function buildPrototypeFromDefinition(
        Form $form,
        array $definition
    ) {
        $cssClass = null;
        $errorMessage = null;
        $startPrototypes = [];
        $rowPrototypes = [];
        $endPrototypes = [];

        if (array_key_exists('class', $definition) == true) {
            $cssClass = $definition['class'];
        }

        if (array_key_exists('errorMessage', $definition) == true) {
            $errorMessage = $definition['errorMessage'];
        }

        if (array_key_exists('startElements', $definition)) {
            foreach ($definition['startElements'] as $rowElement) {
                $startPrototypes[] = $this->createElementPrototype($form, $rowElement);
            }
        }

        if (array_key_exists('rowElements', $definition)) {
            foreach ($definition['rowElements'] as $rowElement) {
                $rowPrototypes[] = $this->createElementPrototype($form, $rowElement);
            }
        }

        if (array_key_exists('endElements', $definition)) {
            foreach ($definition['endElements'] as $rowElement) {
                $endPrototypes[] = $this->createElementPrototype($form, $rowElement);
            }
        }

        //These are things like csrf
        $standardElements = $form->getStandardElements();
        foreach ($standardElements as $element) {
            $endPrototypes[] = $this->createElementPrototype($form, $element);
        }
        
        $formPrototype = new FormPrototype(
            $cssClass,
            $errorMessage,
            $startPrototypes,
            $rowPrototypes,
            $endPrototypes
        );
        
        return $formPrototype;
    }

    
    /**
     * @param $definition
     * @return mixed
     * @throws FCFormsException
     */
    public function createElementPrototype(Form $form, $definition)
    {
        if (array_key_exists('type', $definition) == false) {
            throw new FCFormsException("Form element has no value for type.");
        }

        $elementType = $definition['type'];
        /** @var $element \FCForms\FormElement\ElementPrototype */

        $injector = clone $this->injector;
        $injector->alias('FCForms\Form\Form', get_class($form));
        $injector->share($form);
        $element = $injector->make($elementType);
        $element->initCommon($definition);
        $element->init($definition);

        return $element;
    }

    
//    /**
//     * @param $definition
//     * @return \FCForms\FormElement\AbstractElementPrototype
//     */
//    public function addStartElement($definition)
//    {
//        $formElement = $this->formBuilder->createElement($this, $definition, $this->injector);
//        //Push one or more elements onto the end of array
//        array_push($this->startElements, $formElement);
//
//        return $formElement;
//    }
//
//    /**
//     * @param $definition
//     * @return \FCForms\FormElement\AbstractElementPrototype
//     */
//    public function addEndElement($definition)
//    {
//        $formElement = $this->formBuilder->createElement($this, $definition, $this->injector);
//        //Prepend one or more elements to the beginning of an array
//        array_unshift($this->endElements, $formElement);
//
//        return $formElement;
//    }
//    
    
//    /**
//     * @param $rowID
//     * @param array $dataSource
//     */
//    public function addRowValues($rowID, array $dataSource)
//    {
//        //TODO - adding all the rows at once is saner.
//        $formElementCollection = new FormElementCollection($this, $rowID, $this->rowElements);
//        $formElementCollection->setValues($dataSource);
//        $this->rowFieldCollectionArray[] = $formElementCollection;
//
//        $this->rowIDs[] = $rowID;
//    }
//
//    public function addSubmittedValues($rowName, array $rowValues)
//    {
//        $this->addRowValues($rowName, $rowValues);
//    }
}
