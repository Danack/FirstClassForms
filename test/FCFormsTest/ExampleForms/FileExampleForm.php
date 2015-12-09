<?php

namespace FCFormsTest\ExampleForms;

use FCForms\Form\Form;

class FileExampleForm extends Form
{
    public function getDefinition()
    {
        $definition = array(
            'class'         => 'blogEditForm',

            'startElements' => [
                [
                    'type'  => 'FCForms\FormElement\Title',
                    'value' => 'File upload example',
                    'class' => 'text-center'
                ]
            ],

            'rowElements'   => array(
            ),

            'endElements'   => array(
                array(
                    'isActive',
                    'type'  => 'FCForms\FormElement\File',
                    'label' => 'Image',
                    'name'  => 'image',
                    'placeHolder' => 'Image',
                    'validation' => array(
//                        "Zend\\Validator\\StringLength" => array(
//                            'min' => 20,
//                        ),
                    )
                ),
                
                array(
                    'isActive',
                    'type'  => 'FCForms\FormElement\Text',
                    'label' => 'Description',
                    'name'  => 'description',
                    'placeHolder' => 'Description',
                    'validation' => array(
//                        "Zend\\Validator\\StringLength" => array(
//                            'min' => 20,
//                        ),
                    )
                ),
                array(
                    'submitButton',
                    'type'  => 'FCForms\FormElement\SubmitButton',
                    'label' => null,
                    'name'  => 'upload',
                    'text'  => 'Upload',
                ),
            ),

            'validation'    => array(
                //form level validation.
            )
        );

        return $definition;
    }
}
