<?php


namespace FCForms\FileFetcher;

use FCForms\FileFetcher;
use FCForms\FileUploadException;
use FCForms\UploadedFile;
use Room11\HTTP\Request;

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
    private $request;

    /**
     * @param $files array A CGI style list of files aka $_FILES
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

    }

    /**
     * @param $formFileName
     * @return bool
     */
    public function hasUploadedFile($formFileName)
    {
        return $this->request->hasFormFile($formFileName);
    }

    /**
     * @param $formFileName
     * @throws \FCForms\FileUploadException
     * @return \FCForms\UploadedFile
     */
    public function getUploadedFile($formFileName)
    {
        $fileEntry = $this->request->getFormFile($formFileName);

        if ($fileEntry['error'] != UPLOAD_ERR_OK) {
            throw new FileUploadException(
                "Error detected in upload: ".getFileUploadErrorMeaning($fileEntry['error'])
            );
        }

        if (!is_uploaded_file($fileEntry['tmp_name'])) {
            throw new FileUploadException(
                "File not uploaded. Status [".$fileEntry['error']."] indicated error."
            );
        }

        //TODO - this is a hard-coded implementation. needs to be extracted to be a
        //separate dependency. e.g. to allow file to be stored non-locally.
        $storageName = tempnam(sys_get_temp_dir(), "fileupload_");
        $result = @move_uploaded_file($fileEntry['tmp_name'], $storageName);
        
        if (!$result) {
            throw new FileUploadException(
                "Failed to move uploaded file to temp dir"
            );
        }

        //TODO - bother doing anything with the type?
        //'type' => string 'image/png' (length=9)
        return new UploadedFile(
            $fileEntry['name'],
            $storageName,
            $fileEntry['size']
        );
    }
}
