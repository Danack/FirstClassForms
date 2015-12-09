<?php

namespace FCForms;

class UploadedFile
{
    private $originalName;
    private $filename;
    private $size;

    public function __construct($originalName, $filename, $allegedSize)
    {
        $this->originalName = $originalName;
        $this->filename = $filename;
        $size = @filesize($filename);
        if (!$size) {
            throw new FCFormsException("Failed to read size of uploaded file");
        }

        if ($size != $allegedSize) {
            throw new FCFormsException("Filesize is different from alleged size $size != $allegedSize");
        }

        $this->size = $size;
    }

    /**
     * @return string The filename from the user. This should not be trusted.
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }

    /**
     * @return string The filename of the uploaded file on the current system.
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return int the filesize.
     */
    public function getSize()
    {
        return $this->size;
    }

    public function serialize()
    {
        $data = [];
        $data['originalName'] = $this->originalName;
        $data['filename'] = $this->filename;
        $data['size'] = $this->size;

        return json_encode($data);
    }
    
    public static function deserialize($string)
    {
        $data = json_decode($string, true);
        if (!$data) {
            throw new FCFormsException("Failed to decode string $string");
        }
        //@todo - sanity check tmpName
        return new self(
            $data['originalName'],
            $data['filename'],
            $data['size']
        );
    }

    public function delete()
    {
        unlink($this->filename);
    }
}
