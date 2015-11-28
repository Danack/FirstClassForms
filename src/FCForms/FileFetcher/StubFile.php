<?php


namespace FCForms\FileFetcher;

class StubFile
{
    public $formName;

    public $filename;

    public $originalFilename;

    public function __construct($formName, $filename, $originalFilename)
    {
        $this->formName = $formName;
        $this->filename = $filename;
        $this->originalFilename = $originalFilename;
    }
}
