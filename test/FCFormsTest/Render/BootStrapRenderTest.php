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

    public function testLoginFormRender()
    {
        $loginForm = buildFormWithData('FCFormsTest\ExampleForms\LoginForm');
        $dataStoredInSession = $loginForm->initFromStorage();
        $renderer = new \FCForms\Render\BootStrapRender();
        $html = $renderer->render($loginForm);
    }

    public function testLoginFormRenderWithError()
    {
        $injector = createInjector();
        $errorMessage = "This is a message ".time();
        $loginForm = $injector->make('FCFormsTest\ExampleForms\LoginForm');

        $loginForm->createFromData([]);
        $loginForm->setFormError($errorMessage);
        $dataStoredInSession = $loginForm->initFromStorage();
        $renderer = new \FCForms\Render\BootStrapRender();
        $html = $renderer->render($loginForm);
        $this->assertContains($errorMessage, $html);

        //Store the values in storage and
        $loginForm->saveValuesToStorage();
        
        $loginFormFromStorage = $injector->make('FCFormsTest\ExampleForms\LoginForm');
        $readFromStorage = $loginFormFromStorage->initFromStorage();
        $this->assertTrue($readFromStorage);
        
        $htmlFromStorage = $renderer->render($loginForm);
        $this->assertContains($errorMessage, $htmlFromStorage);
    }
}
