<?php

namespace FCFormsTest\ExampleForms;

use FCForms\Form\Form;

class EmptyForm extends Form
{
    public function getDefinition()
    {
        $definition = array(
            'class'         => 'firstForm',
            'startElements' => array(
            ),
            'rowElements'   => array(
            ),
            'endElements'   => array(
            ),
            'validation'    => array(
                //form level validation.
            )
        );

        return $definition;
    }
}
