<?php

namespace FCFormsTest\Controller;

use FCFormsTest\ExampleForms\ListExamplesForm;
use FCFormsTest\Model\SignupModel;

class Example
{
    public function index(ListExamplesForm $listForm)
    {
        $listForm->createFromData([]);
        return \Tier\getRenderTemplateTier('pages/index');
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
                $signupModel = new SignupModel($email);

                return \Tier\getRenderTemplateTier('pages/listExampleComplete', [$signupModel]);
            }
        }
        else {
            $listForm->createFromData([]);
        }
        
        $listForm->prepareToRender();
        
        return \Tier\getRenderTemplateTier('pages/listExample');
    }
}
