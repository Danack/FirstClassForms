<?php

namespace FCFormsTest\ExampleForms;

use FCForms\Form\Form;

class SignupExampleForm extends Form
{
    public function getDefinition()
    {
        $definition = array(
            'class'         => 'blogEditForm',

            'startElements' => [
                [
                    'type'  => 'FCForms\FormElement\Title',
                    'value' => 'Signup example',
                    'class' => 'text-center'
                ]
            ],

            'rowElements'   => array(
            ),

            'endElements'   => array(
                array(
                    'isActive',
                    'type'  => 'FCForms\FormElement\Text',
                    'label' => 'Email',
                    'name'  => 'email',
                    'placeHolder' => 'Email',
                    'validation' => array(
                        "Zend\\Validator\\StringLength" => array(
                            'min' => 20,
                        ),
                    )
                ),
                array(
                    'type'  => 'FCForms\FormElement\CheckBox',
                    'label' => 'Spam me',
                    'name'  => 'spamMe',
                    'default' => 'true',
                    'helpText' => 'Why would anyone ever opt in to receive marketing messages?'
                ),
                array(
                    'submitButton',
                    'type'  => 'FCForms\FormElement\SubmitButton',
                    'label' => null,
                    'name'  => 'submit',
                    'text'  => 'Update',
                ),
                
            ),

            'validation'    => array(
                //form level validation.
            )
        );

        return $definition;
    }
}
