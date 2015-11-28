<?php

namespace FCForms\Form;

use FCForms\FCFormsException;
use FCForms\FormElement\ElementCollection;
use FCForms\SafeAccess;
use FCForms\FileFetcher;
use Room11\HTTP\VariableMap;
use Auryn\Injector;

abstract class Form
{
    use SafeAccess;

    private $isValid = null;

    private $hasBeenValidated = false;

    /**
     * @var \FCForms\FileFetcher
     */
    protected $fileFetcher;

    /** @var ElementCollection[] */
    public $rowFieldCollectionArray = array();

    public $rowIDs = array();

    protected $forceError = false;
    protected $errorMessage = "Form had errors.";

    protected $class = 'standardForm';

    protected $id = null;

    /** @var  \FCForms\FormElement\ElementCollection[] */
    public $rowElementsArray = array();

    /** @var \FCForms\FormElement\ElementCollection|\FCForms\FormElement\Element[] */
    public $startElements = array();

    /** @var \FCForms\FormElement\ElementCollection|\FCForms\FormElement\Element[] */
    public $endElements = array();

    /**
     * @var \FCForms\Form\DataStore
     */
    private $dataStore;

    /**
     * @var VariableMap
     */
    public $variableMap;

    const FORM_HIDDEN_FQCN = 'formClass';

    /**
     * @var Injector
     */
    protected $injector;

    /**
     * @var FormBuilder
     */
    protected $formBuilder;

    /**
     * @var \FCForms\Form\FormPrototype
     */
    protected $prototype;
    

    public function getFileFetcher()
    {
        return $this->fileFetcher;
    }

    private function __construct()
    {
    }

    /**
     * @param DataStore $dataStore
     * @param VariableMap $variableMap
     * @param FileFetcher $fileFetcher
     * @param Injector $injector
     * @param FormBuilder $formBuilder
     * @return \FCForms\Form\Form
     */
    public static function createBlank(
        DataStore $dataStore,
        VariableMap $variableMap,
        FileFetcher $fileFetcher,
        Injector $injector,
        FormBuilder $formBuilder
    ) {
        $instance = new static();
        
        $instance->dataStore = $dataStore;
        $instance->variableMap = $variableMap;
        $instance->fileFetcher = $fileFetcher;
        $instance->injector = $injector;
        $instance->formBuilder = $formBuilder;

        $definition = $instance->getDefinition();
        
        $instance->prototype = $formBuilder->buildPrototypeFromDefinition(
            $instance,
            $definition
        );

        return $instance;
    }

    /**
     * @return \FCForms\Form\DataStore
     */
    public function getDataStore()
    {
        return $this->dataStore;
    }

    abstract public function getDefinition();

    public function getLabelSpan()
    {
        return 2;
    }

    public function getClassName()
    {
        return $this->class;
    }

    public function getRowIDs()
    {
        return $this->rowIDs;
    }

    protected function getID()
    {
        foreach ($this->endElements as $element) {
            if ($element->getName() == 'formID') {
                return $element->getCurrentValue();
            }
        }
        
        throw new DataMissingException("Could not find formID");
    }

    public function getStandardElements()
    {
        $standardElements = array(
            array(
                'type' => 'FCForms\FormElement\Hidden',
                'name' => self::FORM_HIDDEN_FQCN,
                'value' => get_class($this),
            ),
            array(
                'type' => 'FCForms\FormElement\Hidden',
                'name' => 'formSubmitted',
                'value' => $this->getFormName()
            ),
            array(
                'type' => 'FCForms\FormElement\Hidden',
                'name' => 'formID',
                'value' => uniqid(),
            ),
            array(
                'type' => 'FCForms\FormElement\CSRF',
                'name' => 'csrf',
                'validation' => array(
                    "FCForms\\Validator\\CSRF" => array(),
                )
            ),
        );

        return $standardElements;
    }
//    /**
//     * @param AbstractElementPrototype $element
//     */
//    function getSubmittedValueForElement($rowID, AbstractElementPrototype $element)
//    {
//        if ($element->getName() != null) {
//
//            return $this->variableMap->getVariable($element->getFormName($rowID), null);
//        }
//
//        return null;
//    }
    
            
    /**
     * @throws \Exception
     */
    public function useSubmittedValues()
    {
        $data = [];

        foreach ($this->prototype->startPrototypes as $startPrototype) {
            if ($startPrototype->getName() != null) {
                $rowSpecificName = $startPrototype->getFormName('start');
                $value =  $this->variableMap->getVariable($rowSpecificName, null);
                $data['start'][$rowSpecificName] = $value;
            }
        }
        
        foreach ($this->prototype->endPrototypes as $endPrototype) {
            if ($endPrototype->getName() != null) {
                $rowSpecificName = $endPrototype->getFormName('start');
                $value =  $this->variableMap->getVariable($rowSpecificName, null);
                $data['end'][$rowSpecificName] = $value;
            }
        }

        $rowIDs = $this->variableMap->getVariable("rowIDs", false);

        if ($rowIDs == false) {
            return;
        }

        $rowIDs = explode(',', $rowIDs);

        foreach ($rowIDs as $rowID) {
            $rowID = trim($rowID);
            $rowData = [];
            foreach ($this->prototype->rowPrototypes as $rowPrototype) {
                if ($rowPrototype->getName() != null) {
                    $rowSpecificName = $rowPrototype->getFormName($rowID);
                    $value =  $this->variableMap->getVariable($rowSpecificName, null);
                    $rowData[$rowSpecificName] = $value;
                }
            }
            $this->rowIDs[] = $rowID;
            $data[$rowID] = $rowData;
        }
    }


//    /**
//     * @param $rowID
//     * @param array $dataSource
//     */
//    function addBlankRow($rowID, array $dataSource) {
//        $formElementCollection = new FormElementCollection($this, $rowID, $this->rowElements);
//        $formElementCollection->setValues($dataSource);
//        $this->rowFieldCollectionArray[] = $formElementCollection;
//
//        $this->rowIDs[] = $rowID;
//    }

    /**
     * @param $dataSource
     */
    public function setValues(array $dataSource)
    {
        $this->startElements->setValues($dataSource);
        foreach ($this->rowElementsArray as $rowElements) {
            $rowElements->setValues($dataSource);
        }
        $this->endElements->setValues($dataSource);
    }


    public function getElementByName($elementName)
    {
        foreach ($this->startElements as $element) {
            if ($element->getName() == $elementName) {
                return $element;
            }
        }
        foreach ($this->endElements as $element) {
            if ($element->getName() == $elementName) {
                return $element;
            }
        }
        
        throw new FCFormsException("Form does not have an element named '$elementName'");
    }

    /**
     * @return bool
     */
    public function isSubmitted()
    {
        $element = $this->getElementByName('formSubmitted');
        $formSubmitted = $this->variableMap->getVariable(
            $element->getFormName(),
            false
        );
        if ($formSubmitted == $this->getFormName()) {
            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getAllValues()
    {
        $data = array();
        foreach ($this->startElements as $element) {
            if ($element->hasData()) {
                $data[$element->getName()] = $element->getCurrentValue();
            }
        }

        foreach ($this->rowElementsArray as $rowField) {
            foreach ($rowField->elements as $element) {
                if ($element->hasData()) {
                    $data[$element->getName()] = $element->getCurrentValue();
                }
            }
        }

        foreach ($this->endElements as $element) {
            if ($element->hasData()) {
                $data[$element->getName()] = $element->getCurrentValue();
            }
        }
        
        return $data;
    }

    /**
     * @param $id
     * @param $name
     * @return mixed|null
     */
    public function getValue($id, $name)
    {
        if ($id == 'start') {
            foreach ($this->startElements as $element) {
                if ($element->getName() == $name) {
                    return $element->getCurrentValue();
                }
            }
        }

        foreach ($this->rowElementsArray as $rowElements) {
            if ($rowElements->getID() == $id) {
                $value = $rowElements->getValue($name);
                if ($value !== null) {
                    return $value;
                }
            }
        }

        if ($id == 'end') {
            foreach ($this->endElements as $element) {
                if ($element->getName() == $name) {
                    return $element->getCurrentValue();
                }
            }
        }

        return null;
    }

    /**
     * @param $id
     * @return array|null
     */
    public function getRowValues($id)
    {
        foreach ($this->rowFieldCollectionArray as $rowField) {
            if ($rowField->getID() == $id) {
                return $rowField->getAllValues();
            }
        }

        return null;
    }

    /**
     * @return bool
     */
    public function validate()
    {
        $isValid = true;

        $this->hasBeenValidated = true;

        if ($this->forceError) {
            return false;
        }

        $elementValid = $this->startElements->validate();
        $isValid = ($isValid && $elementValid);

        foreach ($this->rowElementsArray as $rowElements) {
            $isValid = ($isValid && $rowElements->validate());
        }

        $elementValid = $this->endElements->validate();
        $isValid = ($isValid && $elementValid);

        $this->isValid = $isValid;

        return $this->isValid;
    }

    /**
     * @return array
     */
    public function getErrorMessages()
    {
        $errorMessages = [];

        foreach ($this->startElements as $element) {
            $errorMessages += $element->getErrorMessages();
        }

        foreach ($this->rowFieldCollectionArray as $rowFieldCollection) {
            $errorMessages += $rowFieldCollection->getErrorMessages();
        }
        
        foreach ($this->endElements as $element) {
            $errorMessages += $element->getErrorMessages();
        }
        
        return $errorMessages;
    }

    /**
     * @return array|bool
     */
    public function areAllElementsStoreable()
    {
        return $this->prototype->areAllElementsStoreable();
    }

    /**
     *
     */
    public function saveValuesToStorage()
    {
        $serializedData = $this->serialize();
        $sessionName = $this->getSessionName();

        $this->dataStore->setValue($sessionName, $serializedData);
    }

    /**
     * @return bool
     */
    public function readValuesFromStorage($validateIfDataLoaded)
    {
        $sessionName = $this->getSessionName();

        //TODO - need to create an object to set time to prevent form from being resubmitted ages later.
        $storedValues = $this->dataStore->getValue(
            $sessionName,
            false,
            true
        );

        if ($storedValues === false) {
            return false;
        }

        $this->prototype->createElementsFromData($this, $storedValues);
        
        if ($validateIfDataLoaded) {
            $this->validate();
        }

        return true;
    }

    /**
     * @param array $data
     */
    public function createFromData(array $data)
    {
        $this->prototype->createElementsFromData($this, $data);
    }

    /**
     * @param array $startElements
     * @param $rowElementsArray
     * @param $endElements
     */
    public function setElements(
        array $startElements,
        $rowElementsArray,
        $endElements
    ) {
        $this->startElements = new ElementCollection($this, 'start', $startElements);

        $this->rowElementsArray = [];
        foreach ($rowElementsArray as $rowID => $elements) {
            $this->rowElementsArray[$rowID] = new ElementCollection($this, $startElements, $rowID);
        }
        
        $this->endElements = new ElementCollection($this, 'end', $endElements);
    }
    

    /**
     * @return string
     */
    public function getSessionName()
    {
        return $this->getFormName();
    }

    /**
     * @return array
     */
    public function serialize()
    {
        $start = $this->startElements->serialize();
        
        $rows = [];
        foreach ($this->rowElementsArray as $rowElements) {
            $rowID = $rowElements->getID();
            $rows[$rowID] = $rowElements->serialize();
        }

        $end = $this->endElements->serialize();

        $rowIDs = $this->variableMap->getVariable('rowIDs', false);

        $result = array();
        $result['start'] = $start;
        $result['rows'] = $rows;
        $result['end'] = $end;
        $result['rowIDs'] = $rowIDs;
        $result['forceError'] = $this->forceError;
        $result['errorMessage'] = $this->errorMessage;

        return $result;
    }

    /**
     * @throws \Exception
     */
    public function reset()
    {
        foreach ($this->startElements as $element) {
            $element->reset();
        }

        $this->rowElementsArray = array();

        foreach ($this->endElements as $element) {
            $element->reset();
        }
        throw new \Exception("Not implemented safely.");
    }

    /**
     * @param $filename
     * @return \FCForms\UploadedFile
     */
    public function getUploadedFile($filename)
    {
        throw new \Exception("Not currently supported");
        //return $this->variableMap->getUploadedFile($filename);
    }

    /**
     * @return string
     */
    public function getFormName()
    {
        return get_class($this);
    }

    /**
     * @param $errorMessage
     */
    public function setFormError($errorMessage)
    {
        $this->errorMessage = $errorMessage;
        $this->forceError = true;
        //ugh - this is one bit of information spread over multiple variables.
        $this->isValid = false;
    }

    /**
     * Processes the form.
     * @param $callback
     */
    public function process(callable $validCallback, callable $invalidCallback = null)
    {
        $this->useSubmittedValues();
        $this->validate();

        if ($this->isValid) {
            $validCallback($this);
        }
        else if ($invalidCallback) {
            $invalidCallback($this);
        }

        //Valid state can be altered by the call-back
        if (!$this->isValid) {
            $this->saveValuesToStorage();
        }
    }

    /**
     * @return bool|string
     */
    public function getFormErrorMessage()
    {
        if ($this->hasBeenValidated == true) {
            if ($this->isValid == false) {
                return $this->errorMessage;
            }
        }
        
        return false;
    }

    /**
     *
     */
    public function prepareToRender()
    {
        $this->startElements->prepareToRender();

        $this->rowElementsArray = [];
        foreach ($this->rowElementsArray as $rowElements) {
            $rowElements->prepareToRender();
        }

        $this->endElements->prepareToRender();
    }
}
