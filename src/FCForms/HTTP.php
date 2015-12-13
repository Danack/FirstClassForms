<?php


namespace FCForms;

use Auryn\Injector;
use FCForms\Form\Form;
use Room11\HTTP\Request;
use Room11\HTTP\VariableMap;
use Room11\HTTP\Body\RedirectBody;
use Tier\Executable;
use Tier\InjectionParams;

class HTTP
{
    public static function processFormRedirect(
        Request $request,
        Injector $injector,
        VariableMap $variableMap,
        FileFetcher $fileFetcher
    ) {
        $formName = $variableMap->getVariable(Form::FORM_HIDDEN_FQCN);
        $isValidFormFQCN = is_subclass_of($formName, 'FCForms\Form\Form', $allow_string = true);
    
        if (!$isValidFormFQCN) {
            //TODO - this should be logged as a hack attempt.
            return false;
        }
    
        if ($request->getMethod() != 'POST') {
            return false;
        }
  
        /** @var $form \FCForms\Form\Form */
        $form = $injector->make($formName);
    
        //Some elements e.g. passwords, should not be stored in session storage.
        if ($form->canAllElementsBeStored() == false) {
            $fn = function() {
            return false;
            };

            return new Executable($fn, new InjectionParams([$form]));
        }
    
        /** @var $form \FCForms\Form\Form */
        if ($form->isSubmitted($variableMap) == false) {
            return false;
        }
    
        $form->initFromSubmittedData($variableMap, $fileFetcher);
        $form->saveValuesToStorage();

        return new RedirectBody("Form submitted", $request->getPath(), 303);
    }
}
