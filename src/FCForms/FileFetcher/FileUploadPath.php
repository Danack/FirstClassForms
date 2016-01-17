<?php

namespace FCForms\FileFetcher;

use FCForms\FCFormsException;

class FileUploadPath
{
    private $path;

    public function __construct($path)
    {
        $path = (string)$path;
        if (strlen($path) === 0) {
            throw new FCFormsException(
                "Path cannot be empty for FileUploadPath"
            );
        }
        
        if ($path === null) {
            throw new FCFormsException(
                "Path cannot be null for class ".get_class($this)
            );
        }
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }
}
