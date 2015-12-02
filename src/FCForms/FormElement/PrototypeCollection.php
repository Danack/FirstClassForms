<?php

namespace FCForms\FormElement;

use FCForms\Form\Form;
use FCForms\SafeAccess;
use Room11\HTTP\VariableMap;

class PrototypeCollection implements \IteratorAggregate
{
    use SafeAccess;

    /** @var ElementPrototype[] */
    public $prototypes = array();

    protected $className = "collection";

    /**
     * @param Form $form
     * @param $rowID
     * @param $rowElements ElementPrototype[]
     * @throws \Exception
     */
    public function __construct(array $prototypes)
    {
        $this->prototypes = $prototypes;
    }
    
    
    /**
     * @return string
     */
    public function getStyleName()
    {
        return $this->form->getClassName() . "_" . $this->className;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->prototypes);
    }

    /**
     * @param $rowID
     * @param VariableMap $variableMap
     * @return array
     */
    public function getSubmittedDataWithNames($rowID, VariableMap $variableMap)
    {
        $data = [];
        foreach ($this->prototypes as $prototype) {
            $name = $prototype->getName();
            if ($name != null) {
                $data[$name] = $variableMap->getVariable($prototype->getFormName($rowID), null);
            }
        }

        return $data;
    }


    /**
     * Are all of the prototype elements storeable. e.g. passowrds should
     * never be stored in raw values.
     * @return bool
     */
    public function canAllBeStored()
    {
        $allCanBeStored = true;
        foreach ($this->prototypes as $prototype) {
            $allCanBeStored &= $prototype->canBeStored();
        }
        
        return $allCanBeStored;
    }
}
