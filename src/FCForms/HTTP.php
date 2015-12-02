<?php


namespace FCForms;

use Auryn\Injector;
use FCForms\Form\Form;
use Room11\HTTP\Request;
use Room11\HTTP\VariableMap;
use Room11\HTTP\Body\RedirectBody;

class HTTP
{
    public static function processFormRedirect(
        Request $request,
        Injector $injector,
        VariableMap $variableMap
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
    
        if ($form->canAllElementsBeStored() == false) {
            return false;
        }
    
        /** @var $form \FCForms\Form\Form */
        if ($form->isSubmitted($variableMap) == false) {
            return false;
        }
    
        $form->createElementsFromVariableMap($variableMap);
        $form->saveValuesToStorage();
    
        return new RedirectBody("Form submitted", $request->getPath(), 303);
    }
}
