<?php

namespace FCFormsTest;

use FCForms\FormElement\Label;
use FCForms\Render\BootStrapRender;
use FluentDOM\Document as FluentDOMDoc;
use FluentDOM\Xpath as FluentDomXpath;
use Room11\HTTP\VariableMap\ArrayVariableMap;

class BootStrapRenderTest extends BaseTestCase
{
    /**
     * @return \FCForms\FormElement\ElementPrototype
     */
    public function testBasicRendering()
    {
        $form = buildFormWithData('FCFormsTest\ExampleForms\FirstForm');

        $varMap = new ArrayVariableMap([]);
        $this->assertFalse($form->isSubmitted($varMap));
        $form->createFromData([]);
    }

    public function testUnknownElement()
    {
        $form = buildFormWithData('FCFormsTest\ExampleForms\UnrenderableForm');
        $form->createFromData([]);
        $renderer = new \FCForms\Render\BootStrapRender();
        $this->setExpectedException('FCForms\RenderException');
        $renderer->render($form);
    }
    


    
}
