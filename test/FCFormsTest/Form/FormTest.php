<?php

namespace FCFormsTest;

use FCForms\FormElement\Label;
use FCForms\Render\BootStrapRender;
use FluentDOM\Document as FluentDOMDoc;
use FluentDOM\Xpath as FluentDomXpath;
use Room11\HTTP\VariableMap\ArrayVariableMap;

class FormTest extends BaseTestCase
{
    public function checkValuesValid($expectedValues, $values)
    {
        foreach ($expectedValues as $key => $expectedValue) {
            $this->assertArrayHasKey($key, $values);
            $value = $values[$key];
            $this->assertEquals($expectedValue, $value);
        }
    }
    
    /**
     * @return \FCForms\FormElement\ElementPrototype
     */
    public function testBasicSubmission()
    {
        $injector = createInjector();
        /** @var $form \FCFormsTest\ExampleForms\FirstForm */
        $form = $injector->make('FCFormsTest\ExampleForms\FirstForm');

        $renderer = new \FCForms\Render\BootStrapRender();

        $variableMap = new ArrayVariableMap([]);
        $this->assertFalse($form->isSubmitted($variableMap));

        $data = [
          "isActive" => true,
          "testText" => 'foobar',
        ];

        // Check initializing from data is okay
        $form->createFromData($data);
        $values = $form->getAllValues();
        $this->checkValuesValid($data, $values);

        // Render as HTML, and read the values from that.
        $html = $renderer->render($form);
        $variables = getFormVariables($html);
        $submittedVariableMap = new ArrayVariableMap($variables);
        /** @var $formAfterSubmission \FCFormsTest\ExampleForms\FirstForm */
        $formAfterSubmission = $injector->make('FCFormsTest\ExampleForms\FirstForm');
        $this->assertTrue($formAfterSubmission->isSubmitted($submittedVariableMap));

        //$formAfterSubmission->createFromData($variables);
        $formAfterSubmission->createElementsFromVariableMap($submittedVariableMap);
        $submittedValues = $formAfterSubmission->getAllValues();
        $this->checkValuesValid($data, $submittedValues);

        //Store the values in storage and
        $formAfterSubmission->saveValuesToStorage();

        $storageForm = $injector->make('FCFormsTest\ExampleForms\FirstForm');
        /** @var $storageForm \FCFormsTest\ExampleForms\FirstForm */
        $isInitialized = $storageForm->initFromStorage();
        $this->assertTrue($isInitialized);

        $storedValues = $storageForm->getAllValues();
        $this->checkValuesValid($data, $storedValues);
    }
    
    
    public function testGetDataNames()
    {
        $injector = createInjector();
        /** @var $form \FCFormsTest\ExampleForms\FirstForm */
        $form = $injector->make('FCFormsTest\ExampleForms\FirstForm');

        $data = [
            "isActive" => true,
            "testText" => 'foobar',
        ];
        
        $canBeStored = $form->canAllElementsBeStored();
        $this->assertTrue($canBeStored);

        $dataNames = $form->getDataNames();
    }

    public function testPasswordNotStoreable()
    {
        $form = buildFormWithData('FCFormsTest\ExampleForms\FormWithPassword');
        $canBeStored = $form->canAllElementsBeStored();
        $this->assertFalse($canBeStored);
    }
}
