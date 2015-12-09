<?php

namespace FCFormsTest\ExampleForms;

use FCForms\Form\Form;

class FirstForm extends Form
{
    public function getDefinition()
    {
        $definition = array(
            'class'         => 'blogEditForm',

            'startElements' => [
                [
                    'type'  => 'FCForms\FormElement\Title',
                    'value' => 'Simple Form'
                ]
            ],

            'rowElements'   => array(
            ),

            'endElements'   => array(
                array(
                    'type'  => 'FCForms\FormElement\CheckBox',
                    'label' => 'Is active',
                    'name'  => 'isActive',
                ),
                array(
                    'type'  => 'FCForms\FormElement\Text',
                    'label' => 'Some text',
                    'name'  => 'testText',
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
