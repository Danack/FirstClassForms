<?php

namespace FCForms\FormElement;

use FCForms\UploadedFile;
use FCForms\FileFetcher;
use Room11\HTTP\VariableMap;

class File extends ElementPrototype
{
    public function __construct(FileFetcher $fileFetcher)
    {
    }
    
    /**
     * @param array $info
     * @return mixed|void
     */
    public function init(array $info)
    {
    }
    
    /**
     * @return string
     */
    public function getPrototypeCSSClass()
    {
        return "fc_file";
    }
    
    public function serialize(Element $element)
    {
        $data = array($this->name => $element->getCurrentValue()->serialize());
            
        return $data;
    }

    public function deserialize($serializedData)
    {
        $uploadedFile = UploadedFile::deserialize($serializedData);
        return $uploadedFile;
    }

    /**
     * @return string
     */
    public function getCSSClassName()
    {
        return "FileSelect";
    }
    
    public function extractDataFromSubmission(
        VariableMap $variableMap,
        FileFetcher $fileFetcher,
        $rowID
    ) {
        $rowSpecificName = $this->getFormName($rowID);

        if (!$fileFetcher->hasUploadedFile($rowSpecificName)) {
            echo "rowSpecificName $rowSpecificName \n";
            return null;
        }

        $uploadedFile = $fileFetcher->getUploadedFile($rowSpecificName);

        return $uploadedFile->serialize();
    }
}
