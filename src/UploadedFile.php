<?php


namespace Intahwebz;


class UploadedFile
{
    var $name;
    var $tmpName;
    var $size;
    var $contentType;

    //TODO - where is this meant to be set.
    var $defaultContentType = null;

    function __construct($name, $tmpName, $size)
    {
        $this->name = $name;
        $this->tmpName = $tmpName;
        $this->size = $size;

        $this->determineContentType();
    }

    function serialize()
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

    function delete()
    {
        unlink($this->tmpName);
    }

    function determineContentType()
    {
        $pathInfo = pathinfo($this->name);

        if($this->contentType == FALSE){
            if(array_key_exists('extension', $pathInfo) == TRUE){
                try{
                    $contentType = getMimeTypeForFileExtension(strtolower($pathInfo['extension']));
                    $this->contentType = $contentType;
                }
                catch(UnknownMimeType $umt){
                    if(isset($this->defaultContentType) == TRUE){
                        $this->contentType = $this->defaultContentType;
                    }
                    else{
                        throw $umt;//well, we're boned.
                    }
                }
            }
        }
    }
}

 