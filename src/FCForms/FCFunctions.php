<?php

namespace FCForms;

class FCFunctions
{
    public static function determineContentType()
    {
        $pathInfo = pathinfo($this->name);

        if ($this->contentType == false) {
            if (array_key_exists('extension', $pathInfo) == true) {
                try {
                    $contentType = getMimeTypeForFileExtension(strtolower($pathInfo['extension']));
                    $this->contentType = $contentType;
                }
                catch (UnknownMimeType $umt) {
                    if (isset($this->defaultContentType) == true) {
                        $this->contentType = $this->defaultContentType;
                    }
                    else {
                        throw $umt;//well, we're boned.
                    }
                }
            }
        }
    }
}
