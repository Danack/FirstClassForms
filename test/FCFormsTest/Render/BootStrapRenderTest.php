<?php

namespace FCFormsTest;

use FCForms\FormElement\Label;
use FCForms\Render\BootStrapRender;
use FluentDOM\Document as FluentDOMDoc;
use FluentDOM\Xpath as FluentDomXpath;

class BootStrapRenderTest extends BaseTestCase
{
    /**
     * @return \FCForms\FormElement\AbstractElementPrototype
     */
    public function testBasicRendering()
    {
        $injector = createInjector();
        /** @var $form \FCFormsTest\ExampleForms\FirstForm */
        $form = $injector->execute(['FCFormsTest\ExampleForms\FirstForm', 'createBlank']);
        
        $data = [
          'end' => [
              "isActive" => true,
              "testText" => 'foobar',
          ],
        ];

        $form->createFromData($data);
        $renderer = new \FCForms\Render\BootStrapRender();
        $text = $renderer->render($form);
        $variables = getFormVariables($text);
        
        /** @var $formAfterSubmission \FCFormsTest\ExampleForms\FirstForm */
        
        $injector = createInjector($variables);
        $formAfterSubmission = $injector->execute(['FCFormsTest\ExampleForms\FirstForm', 'createBlank']);
        $formAfterSubmission->createFromData($variables);
        $this->assertFalse($form->isSubmitted());
        $this->assertTrue($formAfterSubmission->isSubmitted());
    }
}
