<?php

namespace FCForms\FormElement;

use FCForms\Form\Form;

class Password extends ElementPrototype
{
    /**
     * @param array $info
     * @return mixed|void
     */
    public function init(array $info)
    {
    }

    /**
     * Password fields do not get stored in session to prevent a possible
     * security hole. Any form containing a password does not do a 'postredirectget'
     * automatically, instead the login handler should trigger one, after attempting to
     * validated the password.
     * @return array
     */
    public function serialize(Element $elementInstance)
    {
        return array();
    }
    
    
    /**
     * @return string
     */
    public function getPrototypeCSSClass()
    {
        return "fc_password";
    }


    
    public function canBeStored()
    {
        return false;
    }
}
