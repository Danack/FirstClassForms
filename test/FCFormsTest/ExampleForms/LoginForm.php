<?php


namespace FCFormsTest\ExampleForms;

use FCForms\Form\Form;

class LoginForm extends Form
{
    public function getDefinition()
    {
        $definition = array(

            'requireHTTPS' => true,

            'startElements' => [
                [
                    'type' => 'FCForms\FormElement\Title',
                    'value' => 'Login',
                ]
            ],

            'rowElements' => array(
                
            ),

            'endElements' => array(
                array(
                    'type' => 'FCForms\FormElement\Hidden',
                    'name' => 'returnURL',
                    'validation' => array(
                    )
                ),
                array(
                    'type' => 'FCForms\FormElement\Text',
                    'label' => 'Username',
                    'name' => 'username',
                    'validation' => array(
                        "Zend\\Validator\\StringLength" => array(
                            'min' => 4,
                        ),
                    )
                ),
                array(
                    'type' => 'FCForms\FormElement\Password',
                    'label' => 'Password',
                    'name' => 'password',
                    'validation' => array(
                        "Zend\\Validator\\StringLength" => array(
                            'min' => 4,
                        ),
                    )
                ),
                array(
                    'submitButton',
                    'type' => 'FCForms\FormElement\SubmitButton',
                    'name' => 'submit',
                    'label' => null,
                    'text' => 'Login',
                ),
            ),
    
            'validation' => array(
                //form level validation.
            )
        );

        return $definition;
    }


    public function serialize()
    {
        return parent::serialize();
    }
}
