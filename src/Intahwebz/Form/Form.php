<?php

namespace Intahwebz\Form;

use Intahwebz\FormElement\FormElementCollection;
use Intahwebz\SafeAccess;
use Intahwebz\Session;


abstract class Form {

    use SafeAccess;

    private $isValid = null;

    private $hasBeenValidated = false;

    //TODO - this is referencing something outside of the project.
    /**
     * @var \Intahwebz\FileFetcher
     */
    protected $fileFetcher;

    /** @var FormElementCollection[] */
    var $rowFieldCollectionArray = array();

    /** @var  \Intahwebz\FormElement\AbstractElement[] */
    var $rowElements = array();

    /** @var \Intahwebz\FormElement\AbstractElement[] */
    var $startElements = array();

    /** @var \Intahwebz\FormElement\AbstractElement[] */
    var $endElements = array();

    var $rowIDs = array();

    protected $errorMessage = "Form had errors.";

    var $class = 'standardForm';

    var $id = null;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var \Intahwebz\Request
     */
    public $request;


    function getFileFetcher() {
        return $this->fileFetcher;
    }


    function __construct(Session $session, \Intahwebz\Request $request) {
        $this->session = $session;
        $this->request = $request;

        $definition = $this->getDefinition();
        $this->init($definition);

        //TODO - optimise this to only be used on forms with CSRF?
        //But they all have CSRF!
        $session->setSessionVariable("foo", 'boo');
    }

    /**
     * @return Session
     */
    public function getSession() {
        return $this->session;
    }

    abstract function getDefinition();

    function getLabelSpan() {
        return 2;
    }

    function getClassName() {
        return $this->class;
    }

    function getRowIDs() {
        return $this->rowIDs;
    }

    function getID() {
        foreach ($this->endElements as $element) {
            if ($element->getName() == 'formID') {
                return $element->getCurrentValue();
            }
        }
        
        throw new \Exception("Could not find formID");
    }

    function getStandardElements() {
        $standardElements = array(
            array(
                'type' => \Intahwebz\FormElement\Hidden::class, 
                'name' => 'formClass', 
                'value' => get_class($this),
            ),
            array(
                'type' => \Intahwebz\FormElement\Hidden::class,
                'name' => 'formSubmitted',
                'value' => $this->getFormName()
            ),
            array(
                'type' => \Intahwebz\FormElement\Hidden::class,
                'name' => 'formID',
                'value' => uniqid(),
            ),
            array(
                'type' => \Intahwebz\FormElement\CSRF::class, 
                'name' => 'csrf',
                'validation' => array(
                    "Intahwebz\\Validator\\CSRF" => array(),
                )
            ),
        );

        return $standardElements;
    }

    function init($definition) {
        if (array_key_exists('class', $definition) == true) {
            $this->class = $definition['class'];
        }

        if (array_key_exists('errorMessage', $definition) == true) {
            $this->errorMessage = $definition['errorMessage'];
        }



        if (array_key_exists('startElements', $definition)) {
            foreach ($definition['startElements'] as $rowElement) {
                $formElement = $this->createElement($rowElement);
                $this->startElements[] = $formElement;
            }
        }

        foreach ($definition['rowElements'] as $rowElement) {
            $formElement = $this->createElement($rowElement);
            $this->rowElements[] = $formElement;
        }

        foreach ($definition['endElements'] as $rowElement) {
            $formElement = $this->createElement($rowElement);
            $this->endElements[] = $formElement;
        }

        //These are things like csrf
        $standardElements = $this->getStandardElements();

        foreach ($standardElements as $element) {
            $formElement = $this->createElement($element);
            $this->endElements[] = $formElement;
        }
    }

    /**
     * @param $definition
     * @return \Intahwebz\FormElement\AbstractElement
     */
    function addStartElement($definition) {
        $formElement = $this->createElement($definition);
        array_push ($this->startElements, $formElement);

        return $formElement;
    }

    /**
     * @param $definition
     * @return \Intahwebz\FormElement\AbstractElement
     */
    function addEndElement($definition) {
        $formElement = $this->createElement($definition);
        array_unshift ($this->endElements, $formElement);

        return $formElement;
    }

    /**
     * @param $formElement
     * @return mixed
     * @throws FormInvalidException
     */
    function createElement($formElement) {
        if (array_key_exists('type', $formElement) == false) {
            throw new FormInvalidException("Form element has no value for type.");
        }

        $className = $formElement['type'];
        /** @var $element \Intahwebz\FormElement\AbstractElement */
        $element = new $className($this);
        $element->initCommon($formElement);
        $element->init($formElement);

        return $element;
    }

    /**
     * @throws \Exception
     */
    function useSubmittedValues() {

        foreach ($this->startElements as $element) {
            $element->useSubmittedValue();
        }
        
        foreach ($this->endElements as $element) {
            $element->useSubmittedValue();
        }

        $rowIDs = $this->request->getVariable("rowIDs", false);

        if ($rowIDs == false) {
            return;
        }

        $rowIDs = explode(',', $rowIDs);

        foreach ($rowIDs as $rowID) {
            $rowID = trim($rowID);
            $formElementCollection = new FormElementCollection($this, $rowID, $this->rowElements);
            $formElementCollection->useSubmittedValue();
            $this->rowFieldCollectionArray[] = $formElementCollection;
            $this->rowIDs[] = $rowID;
        }
    }

    /**
     * @param $rowID
     * @param array $dataSource
     */
    function addRowValues($rowID, array $dataSource) {
        $formElementCollection = new FormElementCollection($this, $rowID, $this->rowElements);
        $formElementCollection->setValues($dataSource);
        $this->rowFieldCollectionArray[] = $formElementCollection;

        $this->rowIDs[] = $rowID;
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
    function setValues(array $dataSource) {
        foreach ($this->startElements as $element) {
            $element->setValue($dataSource);
        }
        foreach ($this->rowFieldCollectionArray as $rowField) {
            $rowField->setValues($dataSource);
        }
        foreach ($this->endElements as $element) {
            $element->setValue($dataSource);
        }
    }

    /**
     * @return string
     */
    function render() {
        $output = '';

        if ($this->hasBeenValidated == true) {
            if ($this->isValid == false) {
                $output .= $this->errorMessage;
            }
        }

        $formID = $this->getID();
        
        $output .= "<form action='' method='post' name='vbform' onsubmit='' id='".$formID."' class='well ".$this->class."' enctype='multipart/form-data'>";

        $output .= "<input type='hidden' name='formID' value='".$formID."' />";  
        
        //echo "FormID is $formID";

        $output .= "<div class='row-fluid'>";
        $output .= "<div class='span12' style='padding-left: 20px'>";

        foreach ($this->startElements as $element) {
            $output .= $element->render();
            $output .= "\n";
        }

        foreach ($this->rowFieldCollectionArray as $rowFieldCollection) {
            $output .= $rowFieldCollection->render();
            $output .= "\n";
        }

        $rowIDs = implode(',', $this->rowIDs);
        $output .= "<input type='hidden' name='rowIDs' value='" . $rowIDs . "' />";

        foreach ($this->endElements as $element) {
            $output .= $element->render();
            $output .= "\n";
        }

        $output .= "</form>";

        $output .= "</div>";
        $output .= "<div class='span1'></div>";
        $output .= "</div>";

        return $output;
    }

    /**
     * @return bool
     */
    function isSubmitted() {
        $formSubmitted = $this->request->getVariable('formSubmitted', false);

        if ($formSubmitted == $this->getFormName()) {
            return true;
        }

        return false;
    }


    /**
     * @return bool
     */
    function useDataAvailable() {
        $dataStoredInSession = $this->checkPostRedirectGet();

        if ($dataStoredInSession == true) {
            return true;
        }

        if ($this->isSubmitted() == true) {
            $this->useSubmittedValues();
            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    function getAllValues() {
        $data = array();
        foreach ($this->startElements as $element) {
            $data[$element->getName()] = $element->getCurrentValue();
        }

        foreach ($this->rowFieldCollectionArray as $rowField) {
            foreach ($rowField->elements as $element) {
                $data[$element->getName()] = $element->getCurrentValue();
            }
        }

        foreach ($this->endElements as $element) {
            $data[$element->getName()] = $element->getCurrentValue();
        }
        
        return $data;
    }
    
    /**
     * @param $id
     * @param $name
     * @return mixed|null
     */
    function getValue($id, $name) {

        foreach ($this->startElements as $element) {
            if ($element->getName() == $name) {
                return $element->getCurrentValue();
            }
        }

        foreach ($this->rowFieldCollectionArray as $rowField) {
            if ($rowField->getID() == $id) {
                $value = $rowField->getValue($name);
                if ($value !== null) {
                    return $value;
                }
            }
        }

        foreach ($this->endElements as $element) {
            if ($element->getName() == $name) {
                return $element->getCurrentValue();
            }
        }

        return null;
    }

    
    function getRowValues($id) {
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
    function validate() {
        $isValid = true;

        $this->hasBeenValidated = true;

        foreach ($this->startElements as $element) {
            $elementValid = $element->validate();
            $isValid = ($isValid && $elementValid);
        }

        foreach ($this->rowFieldCollectionArray as $rowFieldCollection) {
            $isValid = ($isValid && $rowFieldCollection->validate());
        }

        foreach ($this->endElements as $element) {
            $elementValid = $element->validate();
            $isValid = ($isValid && $elementValid);
        }

        $this->isValid = $isValid;

        return $this->isValid;
    }

    /**
     *
     */
    function storeValuesInSession() {
        $serializedData = $this->serialize();
        $sessionName = $this->getSessionName();

        $this->session->setSessionVariable($sessionName, $serializedData);
    }

    /**
     * @return string
     */
    public function getSessionName() {
        //return 'formData';
        return $this->getFormName();
    }

    /**
     * @return array
     */
    function serialize() {
        $start = array();
        $rows = array();
        $end = array();

        foreach ($this->startElements as $element) {
            $serialized = $element->serialize();

            if ($serialized !== null) {
                //Some elements are serialized - e.g. submitButton.
                $start[$element->getName()] = $element->getCurrentValue();
            }
        }

        foreach ($this->rowFieldCollectionArray as $rowFieldCollection) {
            $rowID = $rowFieldCollection->getID();
            $rows[$rowID] = $rowFieldCollection->serialize();
        }

        foreach ($this->endElements as $element) {
            $serialized = $element->serialize();

            if ($serialized !== null) {
                //Some elements are serialized - e.g. submitButton.
                $end[$element->getName()] = $element->getCurrentValue();
            }
        }

        $rowIDs = $this->request->getVariable('rowIDs', false);

        $result = array();

        $result['start'] = $start;
        $result['rows'] = $rows;
        $result['end'] = $end;
        $result['rowIDs'] = $rowIDs;

        return $result;
    }

    /**
     * @param $sessionData
     * @return bool
     */
    function useStoredValues($sessionData) {

        //TODO - Data does need to be validated against form version id.
        if ($sessionData == false) {
            return false;
        }

        foreach ($this->startElements as $element) {
            $rowData = $sessionData['start'];
            $element->useData($rowData);
        }

        foreach ($sessionData['rows'] as $rowID => $rowData) {
            $rowID = trim($rowID);
            $formElementCollection = new FormElementCollection($this, $rowID, $this->rowElements);

            $formElementCollection->useData($rowData);
            $this->rowFieldCollectionArray[] = $formElementCollection;
            $this->rowIDs[] = $rowID;
        }

        foreach ($this->endElements as $element) {
            $rowData = $sessionData['end'];
            $element->useData($rowData);
        }

        return true;
    }

    //TODO - need to use session shared across loadbalancer, i.e. session can't be local file.
    /**
     * @return bool
     */
    function checkPostRedirectGet() {
        //TODO - check if POST.
        if ($this->isSubmitted() == true) {

            $this->useSubmittedValues();

            if ($this->request->getMethod() == 'GET') {
                return true;
            }

            //TODO - use Serialize to avoid sticking huge amounts of data in POST.
            //or maybe just post_max_size
            $this->storeValuesInSession();
            //TODO - use response
            //TODO - use request to get uri
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit(0);
        }

        $sessionName = $this->getSessionName();

        //TODO - need to create an object to set time to prevent form from being resubmitted ages later.
        $storedValues = $this->session->getSessionVariable($sessionName, false, true);

        if ($storedValues === false) {
            return false;
        }

        $this->useStoredValues($storedValues);
        return true;
    }

    function reset() {
        foreach ($this->startElements as $element) {
            $element->reset();
        }

        $this->rowFieldCollectionArray = array();

        foreach ($this->endElements as $element) {
            $element->reset();
        }
    }

    /**
     * @param $filename
     * @return \Intahwebz\UploadedFile
     */
    function getUploadedFile($filename) {
        return $this->request->getUploadedFile($filename);
    }

    function getFormName() {
        return get_class($this);
    }


    function addSubmittedValues($rowName, array $rowValues) {
        $this->addRowValues($rowName, $rowValues);
    }
}

