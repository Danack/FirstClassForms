<?php


namespace FCForms\FileFetcher;

use FCForms\FileFetcher;
use FCForms\FileUploadException;
use FCForms\UploadedFile;
use Psr\Http\Message\UploadedFileInterface; 
use Zend\Diactoros\ServerRequest;
use FCForms\FileFetcher\FileUploadPath;

function getFileUploadErrorMeaning($errorCode)
{
    $errorMessages = [
        //uploaded file exceeds the upload_max_filesize directive in php.ini
        UPLOAD_ERR_INI_SIZE => "The file you are trying to upload is too big.",
        // uploaded file exceeds the MAX_FILE_SIZE directive
        // that was specified in the html form
        UPLOAD_ERR_FORM_SIZE => "The file you are trying to upload is too big.",
        UPLOAD_ERR_PARTIAL => "The file you are trying upload was only partially uploaded.",
        UPLOAD_ERR_NO_FILE => "You must select a file for upload.",
        UPLOAD_ERR_NO_TMP_DIR => "Missing a temporary folder.",
        UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk.",
        UPLOAD_ERR_EXTENSION => "A PHP extension stopped the file upload.",
    ];
    
    if (array_key_exists($errorCode, $errorMessages) === true) {
        return $errorMessages[$errorCode];
    }

    return "There was a problem with your upload, error code is ".$errorCode;
}


class PSR7UploadedFileFetcher implements FileFetcher
{
    /** @var \Psr\Http\Message\UploadedFileInterface[] */
    private $uploadedFiles;

    /**
     * @param $files array A CGI style list of files aka $_FILES
     */
    public function __construct(ServerRequest $request, FileUploadPath $fileUploadPath)
    {
        $this->uploadedFiles = $request->getUploadedFiles();
        $this->fileUploadPath = $fileUploadPath;
    }

    /**
     * @param $formFileName
     * @return bool
     * 
     * @Todo - normalise array like names
     * 
     * "foo[details][avatar]" converted to ["foo", "details", "avatar"] ?
     * 
     * array(
     *     'my-form' => array(
     *         'details' => array(
     *             'avatars' => array(
     *                 0 => // UploadedFileInterface instance ,
     *                 1 => // UploadedFileInterface instance ,
     *                 2 => // UploadedFileInterface instance ,
     *             ),
     *         ),
     *     ),
     * )
     * 
     */
    public function hasUploadedFile($formFileName)
    {
        return array_key_exists($formFileName, $this->uploadedFiles);
    }

    /**
     * @return array The name of the uploaded files.
     * 
     */
    public function getAllFileNames()
    {
        return array_keys($this->uploadedFiles);
    }
    
    /**
     * @param $formFileName
     * @throws \FCForms\FileUploadException
     * @return \FCForms\UploadedFile
     */
    public function getUploadedFile($formFileName)
    {
        if (array_key_exists($formFileName, $this->uploadedFiles) === false) {
            throw new FileUploadException(
                "Form does not have a file named $formFileName"
            );
        }
        
        /** @var $fileEntry \Psr\Http\Message\UploadedFileInterface */
        $fileEntry = $this->uploadedFiles[$formFileName];
        
        if (!($fileEntry instanceof UploadedFileInterface)) {
            throw new FileUploadException(
                "Form does not have a file named $formFileName"
            );
        }

        $error = $fileEntry->getError();

        if ($error !== UPLOAD_ERR_OK) {
            throw new FileUploadException(
                "Error detected in upload: ".getFileUploadErrorMeaning($error)
            );
        }

        //TODO - this is a hard-coded implementation. needs to be extracted to be a
        //separate dependency. e.g. to allow file to be stored non-locally.
        $storageName = tempnam(sys_get_temp_dir(), "fileupload_");
        
        try {
            $fileEntry->moveTo($storageName);
        }
        catch (\Exception $e) {
            throw new FileUploadException(
                "Exception moving file: ".$e->getMessage(),
                $e->getCode(),
                $e
            );
        }

        return new UploadedFile(
            $fileEntry->getClientFilename(),
            $storageName,
            $fileEntry->getSize(),
            $fileEntry->getClientMediaType()
        );
    }
}
