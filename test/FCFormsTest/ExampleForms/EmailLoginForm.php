<?php

namespace FCFormsTest\ExampleForms;

use FCForms\Form\Form;

class EmailLoginForm extends Form
{
    public function getDefinition()
    {
        $definition = array(
            'class'         => 'emailLogin',

            'startElements' => [
            ],

            'rowElements'   => array(
            ),

            'endElements'   => array(
                array(
                    'isActive',
                    'type'  => 'FCForms\FormElement\Text',
                    'label' => 'Email',
                    'name'  => 'email',
                    'placeholder' => 'Email'
                ),
                array(
                    'type' => 'FCForms\FormElement\Password',
                    'label' => 'Password',
                    'name' => 'Password',
                    'validation' => array(
                        "Zend\\Validator\\StringLength" => array(
                            'min' => 4,
                        ),
                    )
                ),
                array(
                    'isActive',
                    'type'  => 'FCForms\FormElement\CheckBox',
                    'label' => 'Remember me',
                    'name'  => 'rememberMe',
                ),
                array(
                    'submitButton',
                    'type'  => 'FCForms\FormElement\SubmitButton',
                    'name'  => 'submit',
                    'text'  => 'Sign in',
                ),
            ),

            'validation'    => array(
                //form level validation.
            )
        );

        return $definition;
    }
}
