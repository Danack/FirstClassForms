<?php


namespace Intahwebz\FormElement;

use Intahwebz\Form\Form;

use Intahwebz\Validator\ElementValidator;


abstract class AbstractElement {

    /** @var  string */
    protected $name;

    private $currentValue;
    protected $id;

    /** @var  \Zend\Validator\ValidatorInterface[] */
    public $validationRules = array();

    /** @var \Zend\Filter\AbstractFilter[] */
    public $filters = array();
    
    
    public $errorMessages = array();
    public $label = null;

    public $helpText;


    abstract function getClassName();

    /**
     * @param Form $form
     */
    public function __construct(Form $form) {
        $this->form = $form;
    }

    /**
     * @return string
     */
    public function getStyleName() {
        return $this->form->getClassName() . '_' . $this->getClassName();
    }

    /**
     * @return string
     */
    public function getFormName() {
        if ($this->id != null) {
            return $this->id . "_" . $this->name;
        }

        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getCurrentValue() {
        return $this->currentValue;
    }

    /**
     * Most form elements validate on the current value. However some, like CSRF, will validate
     * against the value stored in the session.
     * @return mixed
     */
    public function getValidationValue() {
        return $this->currentValue;
    }


    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param $id
     */
    public function setID($id) {
        $this->id = $id;
    }

    /**
     * @param array $info
     * @return mixed
     */
    abstract function init(array $info);

    /**
     * @return mixed
     */
    abstract function render();

    /**
     *
     */
    public function useSubmittedValue() {
        if ($this->name != null) {
            $value = getVariable($this->getFormName(), null);
            $this->setCurrentValue($value);
        }
    }

    /**
     * @param $value
     */
    public function setCurrentValue($value) {
        
        foreach ($this->filters as $filter) {
            $value = $filter->filter($value);
        }

        $this->currentValue = $value;
    }

    /**
     * @param $data
     */
    public function useData($data) {
        if ($this->name != null) {
            if (array_key_exists($this->getName(), $data) == true) {
                $value = $data[$this->getName()];
                $this->setCurrentValue($value);
            }
        }
    }

    /**
     * @param $formElement
     */
    public function initCommon($formElement) {
        if (array_key_exists('label', $formElement) == true) {
            $this->label = $formElement['label'];
        }
        if (array_key_exists('name', $formElement) == true) {
            $this->name = $formElement['name'];
        }
        if (array_key_exists('value', $formElement) == true) {
            $this->currentValue = $formElement['value'];
        }

        if (array_key_exists('filter', $formElement) == true) {
            $this->addFilters($formElement['filter']);
        }

        if (array_key_exists('validation', $formElement) == true) {
            $this->addValidationRules($formElement['validation']);
        }
    }


    public function addFilters($filterArray) {

        foreach ($filterArray as $filterClassname => $options) {
            if (is_object($options) == true) {
                $this->filters[] = $options;
            }
            else{
                $validator = new $className($options);
                $this->filters[] = $validator;
            }
        }
    }

    /**
     * @param $validationInfoArray
     */
    public function addValidationRules($validationInfoArray) {
        foreach ($validationInfoArray as $className => $options) {
            
            if (is_object($options) == true) {
                $this->validationRules[] = $options;
            }
            else{
                $validator = new $className($options);
                $this->validationRules[] = $validator;
            }
        }
    }

    /**
     * @param $dataSource
     */
    public function setValue($dataSource) {
        if (array_key_exists($this->name, $dataSource) == true) {
            $this->setCurrentValue($dataSource[$this->name]);
        }
    }

    /**
     * @return bool
     */
    public function validate() {
        $isValid = true;

        foreach ($this->validationRules as $validationRule) {

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
     * @return array
     */
    function serialize() {
        return array($this->name => $this->currentValue);
    }

    function reset() {
        $this->setCurrentValue(null);
    }
}

