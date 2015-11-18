<?php


namespace Intahwebz\Utils;

use Intahwebz\FileFetcher;
use Intahwebz\FileUploadException;

function getNormalizedFILES($files)
{
    $newFiles = array();
    foreach ($files as $fieldName => $fieldValue) {
        foreach ($fieldValue as $paramName => $paramValue) {
            /** @noinspection PhpUnusedLocalVariableInspection */
            foreach ((array)$paramValue as $index => $value) {
                $newFiles[$fieldName][$paramName] = $value;
            }
        }
    }
    return $newFiles;
}

function getFileUploadErrorMeaning($errorCode)
{
    switch ($errorCode) {
        case (UPLOAD_ERR_OK):{ //no error; possible file attack!
            return "There was a problem with your upload.";
        }
        case (UPLOAD_ERR_INI_SIZE): {
            //uploaded file exceeds the upload_max_filesize directive in php.ini
            return "The file you are trying to upload is too big.";
        }
        case (UPLOAD_ERR_FORM_SIZE): {
            // uploaded file exceeds the MAX_FILE_SIZE directive
            // that was specified in the html form
            return "The file you are trying to upload is too big.";
        }
        case UPLOAD_ERR_PARTIAL: {//uploaded file was only partially uploaded
            //Todo - allow partial uploads
            return "The file you are trying upload was only partially uploaded.";
        }
        case (UPLOAD_ERR_NO_FILE): {//no file was uploaded
            return "You must select a file for upload.";
        }

        //TODO - handle these
// UPLOAD_ERR_NO_TMP_DIR
// UPLOAD_ERR_CANT_WRITE
// UPLOAD_ERR_EXTENSION

        default: { //a default error, just in case!  :)
            return "There was a problem with your upload, error code is ".$errorCode;
        }
    }
}





class UploadedFileFetcher implements FileFetcher
{
    private $files;

    /**
     * @param $files array A CGI style list of files aka $_FILES
     */
    public function __construct($files)
    {
        $this->files = getNormalizedFILES($files);
    }

    public function hasUploadedFile($formFileName)
    {
        if (!array_key_exists($formFileName, $this->files)) {
            return false;
        }

        if ($this->files[$formFileName]['error'] == UPLOAD_ERR_OK) {
            return true;
        }

        return false;
    }

    /**
     * @param $formFileName
     * @throws \Intahwebz\FileUploadException
     * @return \Intahwebz\UploadedFile
     */
    public function getUploadedFile($formFileName)
    {
        $files = $this->files;

        if (isset($files[$formFileName]) == false) {
            throw new FileUploadException("File not uploaded. \$files[".$formFileName."] is not set.");
        }
        else {
            if ($files[$formFileName]['error'] == UPLOAD_ERR_OK) {
                if (is_uploaded_file($files[$formFileName]['tmp_name'])) {
                    return new \Intahwebz\UploadedFile(
                        $files[$formFileName]['name'],
                        $files[$formFileName]['tmp_name'],
                        $files[$formFileName]['size']
                    );
                }
                else {
                    throw new FileUploadException(
                        "File not uploaded. Status [".$files[$formFileName]['error']."] indicated error."
                    );
                }
            }
            else {
                throw new FileUploadException(
                    "Error detected in upload: ".getFileUploadErrorMeaning($files[$formFileName]['error'])
                );
            }
        }
    }
}
