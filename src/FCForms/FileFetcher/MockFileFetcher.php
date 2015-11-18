<?php

namespace Intahwebz\Utils;

use Intahwebz\FileFetcher;

class MockFileFetcher implements FileFetcher
{

    public function __construct($filename, $originalFilename) {
        $this->filename = $filename;
        $this->originalFilename = $originalFilename;
    }

    public function hasUploadedFile($formFileName)
    {
        if (strcmp($formFileName, $this->filename) === 0) {
            return true;
        }

        return false;
    }

    public function getUploadedFile($formFileName) {
        $tempFilename = tempnam ('/tmp', 'mockFileFetcher');

        if (file_exists($this->filename) == false) {
            throw new \InvalidArgumentException("File ".$this->filename." does not exist.");
        }

        copy($this->filename, $tempFilename);

        return new \Intahwebz\UploadedFile(
            $this->originalFilename,
            $tempFilename,
            filesize($tempFilename)
        );
    }
}
