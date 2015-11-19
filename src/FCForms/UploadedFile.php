<?php

namespace FCForms;

class UploadedFile
{
    private $name;
    private $tmpName;
    private $size;
    private $contentType;

//    //TODO - where is this meant to be set.
//    var $defaultContentType = null;

    public function __construct($name, $tmpName, $size)
    {
        $this->name = $name;
        $this->tmpName = $tmpName;
        $this->size = $size;

        //$this->determineContentType();
    }

    public function serialize()
    {
        $data = [];
        $data['name'] = $this->name;
        $data['tmpName'] = $this->tmpName;
        $data['size'] = $this->size;

        return json_encode($data);
    }
    
    public static function deserialize($string)
    {
        $data = json_decode($string, true);
        if (!$data) {
            throw new \Exception("Failed to decode string $string");
        }
        //@todo - sanity check tmpName
        //@TODO - check file exists.
        return new self(
            $data['name'],
            $data['tmpName'],
            $data['size']
        );
    }

    public function delete()
    {
        unlink($this->tmpName);
    }
}
