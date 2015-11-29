<?php

namespace FCFormsTest\ExampleForms;

use FCForms\Form\Form;

class UnrenderableForm extends Form
{
    public function getDefinition()
    {
        $definition = array(
            'class'         => 'blogEditForm',

            'startElements' => [
                [
                    'type'  => 'FCFormsTest\FormElement\UnrenderableElement',
                    'value' => 'This shouldnt be rendered'
                ]
            ],

            'rowElements'   => array(
            ),

            'endElements'   => array(
            ),

            'validation'    => array(
            )
        );

        return $definition;
    }
}
