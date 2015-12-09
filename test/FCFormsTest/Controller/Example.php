<?php

namespace FCFormsTest\Controller;

use FCFormsTest\ExampleForms\FileExampleForm;
use FCFormsTest\ExampleForms\ListExamplesForm;
use FCFormsTest\ExampleForms\SignupExampleForm;
use FCFormsTest\Model\SignupModel;
use FCFormsTest\Model\Debug;
use FCForms\UploadedFile;

class Example
{
    public function index(ListExamplesForm $listForm)
    {
        $listForm->initFromData([]);
        return \Tier\getRenderTemplateTier('pages/index');
    }

    
    /**
     * @param ListExamplesForm $listForm
     * @return \Tier\Executable
     */
    public function signupExample(SignupExampleForm $listForm)
    {
        $dataStoredInSession = $listForm->initFromStorage();
        if ($dataStoredInSession) {
            $isValid = $listForm->validate();
            if ($isValid) {
                $email =  $listForm->getValue('end', 'email');
                $spamMe =  $listForm->getValue('end', 'spamMe');
                $signupModel = new SignupModel($email, $spamMe);

                return \Tier\getRenderTemplateTier('pages/signupComplete', [$signupModel]);
            }
        }
        else {
            $listForm->initFromData([]);
        }
        
        $listForm->prepareToRender();
        
        return \Tier\getRenderTemplateTier('pages/signup');
    }

    public function fileExample(FileExampleForm $fileForm)
    {
        $dataStoredInSession = $fileForm->initFromStorage();
        if ($dataStoredInSession) {
            $isValid = $fileForm->validate();
            if ($isValid) {
                /** @var  $uploadedFile UploadedFile */
                $uploadedFile = $fileForm->getValue('end', 'image');
                
                $debug = new Debug();
                
                $debug->add("Original name: ".$uploadedFile->getOriginalName());
                $debug->add("Filename name: ".$uploadedFile->getFilename());
                $debug->add("Size : ".$uploadedFile->getSize());

              
                            
                
                return \Tier\getRenderTemplateTier('pages/fileComplete', [$debug]);
            }
        }
        else {
            $fileForm->initFromData([]);
        }
        
        $fileForm->prepareToRender();
        
        return \Tier\getRenderTemplateTier('pages/file');
    }
    
    /**
     * @param ListExamplesForm $listForm
     * @return \Tier\Executable
     */
    public function listExample(ListExamplesForm $listForm)
    {
        $dataStoredInSession = $listForm->initFromStorage();

        if ($dataStoredInSession) {
            $isValid = $listForm->validate();
            if ($isValid) {
                $email =  $listForm->getValue('end', 'email');
                $spamMe =  $listForm->getValue('end', 'spamMe');
                $signupModel = new SignupModel($email, $spamMe);

                return \Tier\getRenderTemplateTier('pages/listExampleComplete', [$signupModel]);
            }
        }
        else {
            $listForm->initFromData([]);
        }
        
        $listForm->prepareToRender();
        
        return \Tier\getRenderTemplateTier('pages/listExample');
    }
}
