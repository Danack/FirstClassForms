<?php


namespace FCFormsTest\Data;

class Link
{
    public $path;
    public $description;
    public $isCurrent;
    
    public function __construct($path, $description, $isCurrent)
    {
        $this->path = $path;
        $this->description = $description;
        $this->isCurrent = $isCurrent;
    }
}
