<?php

namespace FCFormsTest\Controller;

use FCFormsTest\ExampleForms\ListExamplesForm;
use FCFormsTest\ExampleForms\SignupExampleForm;
use FCFormsTest\Model\SignupModel;

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
