<?php

namespace FCFormsTest\ExampleForms;

use FCForms\Form\Form;

class FormWithPassword extends Form
{
    public function getDefinition()
    {
        $definition = array(
            'class'         => 'blogEditForm',

            'startElements' => [
            ],

            'rowElements'   => array(
            ),

            'endElements'   => array(
                array(
                    'isActive',
                    'type'  => 'FCForms\FormElement\Password',
                    'label' => 'Password',
                    'name' => 'password',
                ),
            ),

            'validation'    => array(
                //form level validation.
            )
        );

        return $definition;
    }
}
