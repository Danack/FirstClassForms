<?php

namespace FCForms;

interface FileFetcher
{
    /**
     * @param $formFileName
     * @return \FCForms\UploadedFile
     * @throws \InvalidArgumentException
     */
    public function getUploadedFile($formFileName);

    /**
     * @param $formFileName
     * @return bool
     */
    public function hasUploadedFile($formFileName);
}
