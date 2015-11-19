<?php

namespace FCForms\FormElement;

use FCForms\UploadedFile;

class File extends AbstractElement
{
    /**
     * @param array $info
     * @return mixed|void
     */
    public function init(array $info)
    {

    }
    
    public function serialize()
    {
        return array($this->name => $this->getCurrentValue()->serialize());
    }
    
    public function deserialize($serializedData)
    {
        $uploadedFile = UploadedFile::deserialize($serializedData);
        //$value = $this->deserialize($data[$this->getName()]);
        return $uploadedFile;
    }
    

    public function useSubmittedValue()
    {
        if ($this->form->isSubmitted()) {
            $fileFetcher = $this->form->getFileFetcher();

            if (!$fileFetcher->hasUploadedFile($this->getFormName())) {
                return;
            }

            $uploadedFile = $fileFetcher->getUploadedFile($this->getFormName());

            if ($uploadedFile != false) {
                $tmpName = tempnam(sys_get_temp_dir(), "fileupload_");
                $result = move_uploaded_file($uploadedFile->tmpName, $tmpName);

                if ($result == true) {
                    $uploadedFile->tmpName = $tmpName;
                    $serializedData = $uploadedFile->serialize();
                    $this->form->getDataStore()->storeData($this->getID(), $serializedData);
                    $this->setCurrentValue($uploadedFile);
                }
            }
            else {
                // try to get $userUploadedFile from session.
                $serializedData = $this->form->getDataStore()->getData($this->getID(), false, true);
                $uploadedFile = UploadedFile::deserialize($serializedData);
                $this->setCurrentValue($uploadedFile);
            }
        }
    }
    
    //    // Ensure the form is using correct enctype
    //$form->setAttribute('enctype', 'multipart/form-data');

    /**
     * @return string
     */
    public function getCSSClassName()
    {
        return "FileSelect";
    }
        
    /**
     * @return mixed|string
     */
    public function render()
    {
        $output = "";

        if (count($this->errorMessages) > 0) {
            $output .= "<div class='row-fluid'>";
            $output .= "<div class='errorMessage span12'>";
            foreach ($this->errorMessages as $errorMessage) {
                $output .= $errorMessage;
            }
            $output .= "</div>";
            $output .= "</div>";
        }

        $output .= "<div class='row-fluid'>";
        $remainingSpan = 'span12';

        if ($this->label !== null) {
            $labelSpan = "span" . $this->form->getLabelSpan();
            $remainingSpan = "span" . (12 - $this->form->getLabelSpan());
            $output .= "<label class='$labelSpan' for='" . $this->getFormName() . "'>" . $this->label . "</label>";
        }

        $output .= "<div class='$remainingSpan'>";

        $uploadedFile = $this->getCurrentValue();
        if (!$uploadedFile instanceof UploadedFile) {
            echo "wrong type";
            exit(0);
        }

        if ($uploadedFile == null) {
            $output .= "<input type='file' name='".$this->getFormName()."' value='' />";
        }
        else {
            //show info about $userUploadedFile
            echo "File uploaded ".safeText($uploadedFile->name);
        }
        
        $output .= "</div>";
        $output .= "</div>";

        return $output;
    }

    public function reset()
    {
        $this->setCurrentValue(null);
        $serializedData = $this->form->getDataStore()->getData($this->getID(), null, true);

        if ($serializedData) {
            $uploadedFile = unserialize($serializedData);
            /** @var $uploadedFile UploadedFile */
            echo "need to delete ".$uploadedFile->tmpName;
        }
    }
}
