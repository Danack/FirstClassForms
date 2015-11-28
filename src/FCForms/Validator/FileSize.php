<?php

namespace FCForms\Validator;

use FCForms\UploadedFile;
use FCForms\FCFormsException;

/**
 * Validator for the size of all files which will be validated in sum
 */
class FileSize extends \Zend\Validator\AbstractValidator
{
    private $minSize = 0;
    private $maxSize = 0;

    /**
     * @const string Error constants
     */
    const TOO_BIG      = 'fileTooBig';
    const TOO_SMALL    = 'fileTooSmall';
    const NOT_READABLE = 'fileSizeNotReadable';

    /**
     * @var array Error message templates
     */
    protected $messageTemplates = array(
        self::TOO_BIG      => "File too big. Maximum size is '%max%' but file has size '%size%'.",
        self::TOO_SMALL    => "File too small. Minimum size is '%min%' but file has '%size%'.",
        self::NOT_READABLE => "File could not be read.",
    );

    /**
     * Sets validator options
     *
     * Min limits the used disk space for all files, when used with max=null it is the maximum file size
     * It also accepts an array with the keys 'min' and 'max'
     */
    public function __construct($options = null)
    {
        if (array_key_exists('minSize', $options)) {
            $this->minSize = $options['minSize'];
        }

        if (array_key_exists('maxSize', $options)) {
            $this->maxSize = $options['maxSize'];
        }
  
        parent::__construct($options);
    }

    /**
     * Returns true if and only if the disk usage of all files is at least min and
     * not bigger than max (when max is not null).
     *
     * @param  string $value Real file to check for size
     * @return bool
     */
    public function isValid($value)
    {
        if (!($value instanceof UploadedFile)) {
            throw new FCFormsException("FileSize validator can only accept an object of type UploadedFile");
        }
        else {
            /** @var $uploadedFile UploadedFile */
            $uploadedFile = $value;

            $fileSize = $uploadedFile->getSize();

            if ($this->minSize > 0) {
                if ($this->minSize > $fileSize) {
                    $this->error(self::TOO_SMALL, $fileSize);
                    return false;
                }
            }

            if ($this->maxSize > 0) {
                if ($this->maxSize < $fileSize) {
                    $this->error(self::TOO_BIG, $fileSize);
                    return false;
                }
            }
        }

        return true;
    }
}
