<?php

namespace Intahwebz;

interface FileFetcher {

    /**
     * @param $formFileName
     * @return \Intahwebz\UploadedFile
     * @throws \InvalidArgumentException
     */
    public function getUploadedFile($formFileName);

    /**
     * @param $formFileName
     * @return bool
     */
    public function hasUploadedFile($formFileName);
}




 