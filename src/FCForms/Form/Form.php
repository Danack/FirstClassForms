<?php

namespace FCForms\Form;

use FCForms\DataStore;
use FCForms\FCFormsException;
use FCForms\FormElement\ElementCollection;
use FCForms\SafeAccess;
use FCForms\FileFetcher;
use Room11\HTTP\VariableMap;
use Auryn\Injector;
use FCForms\FileFetcher\StubFileFetcher;

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
    
    protected $errorMessage = null;

    protected $styleName = 'fcform';

    protected $id = null;
    
    protected $htmlID = null;

    /** @var  \FCForms\FormElement\ElementCollection[] */
    public $rowElementsArray = null;

    /** @var \FCForms\FormElement\ElementCollection|\FCForms\FormElement\Element[] */
    public $startElements = null;

    /** @var \FCForms\FormElement\ElementCollection|\FCForms\FormElement\Element[] */
    public $endElements = null;

    /**
     * @var \FCForms\DataStore
     */
    private $dataStore;

    const FORM_HIDDEN_FQCN = 'formClass';

    /**
     * @var \FCForms\FormBuilder
     */
    protected $formBuilder;

    /**
     * @var \FCForms\Form\FormPrototype
     */
    protected $prototype;
    
    /** @var null|string Whether the form has been initialized  */
    private $initialized = null;
    
    
    abstract public function getDefinition();

    /**
     * @param DataStore $dataStore
     * @param FileFetcher $fileFetcher
     * @param AurynFormBuilder $formBuilder
     * @internal param VariableMap $variableMap
     * @internal param Injector $injector
     * @return Form
     */
    public function __construct(
        DataStore $dataStore,
        FileFetcher $fileFetcher,
        AurynFormBuilder $formBuilder
    ) {
        $this->dataStore = $dataStore;
        $this->fileFetcher = $fileFetcher;
        $this->formBuilder = $formBuilder;
        $definition = $this->getDefinition();
        
        $this->prototype = $formBuilder->buildFormPrototypeFromDefinition(
            $this,
            $definition
        );
    }

    public function getFileFetcher()
    {
        return $this->fileFetcher;
    }

    public function getStyleName()
    {
        return $this->styleName;
    }

    public function getRowIDs()
    {
        return $this->rowIDs;
    }

    public function getHTMLID()
    {
        foreach ($this->endElements as $element) {
            if ($element->getName() == 'formID') {
                return $element->getCurrentValue();
            }
        }
        
        throw new DataMissingException("Could not find formID");
    }

    /**
     * @return array
     */
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

    /**
     * @param array $data
     */
    public function initFromData(array $data)
    {
        $this->prototype->createElementsFromData($this, $data);
        $this->initialized = 'initFromData';
    }

    /**
     * @param VariableMap $variableMap
     */
    public function initFromSubmittedData(
        VariableMap $variableMap,
        FileFetcher $fileFetcher = null
    ) {
        if ($fileFetcher == null) {
            $fileFetcher = new StubFileFetcher([]);
        }

        $this->prototype->createElementsFromSubmittedData($this, $variableMap, $fileFetcher);
        $this->initialized = 'initFromVariableMap';
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

    /**
     * @param $elementName
     * @return \FCForms\FormElement\Element
     * @throws FCFormsException
     */
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
    public function isSubmitted(VariableMap $variableMap)
    {
        $prototype = $this->prototype->getPrototypeByName('formSubmitted');
        $formSubmitted = $variableMap->getVariable(
            $prototype->getFormName('start'),
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
    public function canAllElementsBeStored()
    {
        return $this->prototype->canAllElementsBeStored();
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
    public function initFromStorage()
    {
        $sessionName = $this->getSessionName();
        
        //TODO - need to create an object to set time to prevent form from being resubmitted ages later.
        $storedValues = $this->dataStore->getValue(
            $sessionName,
            false,
            true
        );

        if ($storedValues === false) {
            $this->prototype->createElementsFromData($this, []);
            $this->initialized = 'initFromStorageNoData';
            return false;
        }

        $topLevelData = array_merge($storedValues['data']['start'], $storedValues['data']['end']);
        $rowData = $storedValues['data']['rows'];
        $this->prototype->createElementsFromData($this, $topLevelData, $rowData);
        $this->forceError = $storedValues['forceError'];
        $this->errorMessage = $storedValues['errorMessage'];
        $this->initialized = 'initFromStorageWithData';
        
        return true;
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
        $rowIDs = [];
        foreach ($this->rowElementsArray as $rowElements) {
            $rowID = $rowElements->getID();
            $rows[$rowID] = $rowElements->serialize();
            $rowIDs[] = $rowID;
        }

        $end = $this->endElements->serialize();

        $result = array();
        $result['data']['start'] = $start;
        $result['data']['rows'] = $rows;
        $result['data']['end'] = $end;
        $result['rowIDs'] = implode(',', $rowIDs);
        $result['forceError'] = $this->forceError;
        $result['errorMessage'] = $this->errorMessage;

        return $result;
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
    }

    /**
     * @return bool|string
     */
    public function getErrorMessage()
    {
        if ($this->errorMessage) {
            return $this->errorMessage;
        }

        return $this->prototype->defaultErrorMessage;
    }

    public function hasError()
    {
        if (!$this->isValid) {
            return true;
        }
        else if ($this->forceError == true) {
            return true;
        }
        return false;
    }
    
    /**
     *
     */
    public function prepareToRender()
    {
        if ($this->initialized == null) {
            $message = sprintf(
                "Form %s has not been initialized with data. Did you remember to share it?",
                get_class($this)
            );
            throw new FCFormsException($message);
        }

        $this->startElements->prepareToRender();

        $this->rowElementsArray = [];
        foreach ($this->rowElementsArray as $rowElements) {
            $rowElements->prepareToRender();
        }

        $this->endElements->prepareToRender();
    }

    /**
     * @return array
     */
    public function getDataNames()
    {
        $data = [];
        $data['start'] = $this->prototype->getStartDataNames();
        $data['rows'] = $this->prototype->getRowDataNames();
        $data['end'] = $this->prototype->getEndDataNames();
        
        return $data;
    }
}
