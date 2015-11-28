<?php

namespace FCForms\FileFetcher;

use FCForms\FileFetcher;
use FCForms\UploadedFile;
use FCForms\FileUploadException;

class StubFileFetcher implements FileFetcher
{
    /** @var \FCForms\FileFetcher\StubFile[]  */
    private $stubFiles = [];

    public function __construct(array $stubFiles)
    {
        $this->stubFiles = $stubFiles;
    }

    public function hasUploadedFile($formName)
    {
        foreach ($this->stubFiles as $stubFile) {
            if ($stubFile->formName == $formName) {
                return true;
            }
        }

        return false;
    }

    public function getUploadedFile($formFileName)
    {
        if (!$this->hasUploadedFile($formFileName)) {
            //TODO - make functon on exception class
            throw new FileUploadException("File $formFileName not found ");
        }
        
        $uploadedStubFile = null;
        foreach ($this->stubFiles as $stubFile) {
            if ($stubFile->formName == $formFileName) {
                $uploadedStubFile = $stubFile;
                break;
            }
        }

        if (file_exists($uploadedStubFile->filename) == false) {
            throw new \InvalidArgumentException("File ".$uploadedStubFile->filename." does not exist.");
        }

        $tempFilename = tempnam('/tmp', 'mockFileFetcher');

        copy($uploadedStubFile->filename, $tempFilename);

        return new UploadedFile(
            $uploadedStubFile->originalFilename,
            $tempFilename,
            filesize($tempFilename)
        );
    }
}
