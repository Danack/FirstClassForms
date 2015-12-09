<?php

namespace FCForms\FormElement;

use Room11\HTTP\VariableMap;
use FCForms\FileFetcher;

class CheckBox extends ElementPrototype
{
    public function init(array $info)
    {
    }

    /**
     * @return string
     */
    public function getPrototypeCSSClass()
    {
        return 'fc_checkbox';
    }

    public function extractDataFromSubmission(
        VariableMap $variableMap,
        FileFetcher $fileFetcher,
        $rowID
    ) {
        $rowSpecificName = $this->getFormName($rowID);
        $value = $variableMap->getVariable($rowSpecificName, null);
        
        if ($value) {
            return true;
        }

        return false;
    }
}
