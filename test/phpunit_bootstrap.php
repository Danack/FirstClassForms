<?php

use Auryn\Injector;
use Room11\HTTP\VariableMap\ArrayVariableMap;
use FCForms\FileFetcher\StubFileFetcher;
use FCFormsTest\ExampleForms\EmptyForm;
use FCForms\Render\BootStrapRender;

$autoloader = require(__DIR__.'/../vendor/autoload.php');

$autoloader->add('JigTest', [realpath('./').'/test/']);
$autoloader->add(
    "Jig\\PHPCompiledTemplate",
    [realpath(realpath('./').'/tmp/generatedTemplates/')]
);


/**
 * @return Injector
 * @throws \Auryn\ConfigException
 */
function createInjector()
{
    $injector = new Injector();
    $injector->alias('FCForms\Form\DataStore', 'FCFormsTest\Form\ArrayDataStore');
    $injector->share('FCForms\Form\DataStore');
    $injector->alias('Room11\HTTP\VariableMap', 'Room11\HTTP\VariableMap\ArrayVariableMap');

    $injector->alias('FCForms\FileFetcher', 'FCForms\FileFetcher\StubFileFetcher');
    $injector->share(new StubFileFetcher([]));
    $injector->share($injector);

    return $injector;
}

/**
 * @param $formClassName
 * @return \FCFormsTest\ExampleForms\FirstForm
 * @throws Exception
 */
function buildFormWithData($formClassName)
{
    if (!is_subclass_of($formClassName, 'FCForms\Form\Form', true)) {
        throw new \Exception('FQCN [$formClassName] is not a subclass of FCForms\Form\Form');
    }

    $injector = createInjector();
    /** @var $form \FCFormsTest\ExampleForms\FirstForm */
    $form = $injector->execute([$formClassName, 'createBlank']);

    return $form;
}


//$injector = createInjector();
//
//$form = $injector->execute(['FCFormsTest\ExampleForms\FirstForm', 'createBlank']); 
//
///** @var $form \FCForms\Form\Form */
//if ($form->isSubmitted()) {
//    echo "Form was submitted";
//    $form->useSubmittedValues();
//    $form->storeValuesInSession();
//}
//else {
//    echo "was not submitted";
//
//    $data = [
//      'end' => [
//          "isActive" => true,
//          "testText" => 'foobar',
//      ],  
//    ];
//
//    $form->createFromData($data);
//}
//
//$renderer = new \FCForms\Render\BootStrapRender();
//
//$text = $renderer->render($form);
//$variables = getFormVariables($text);
//
//var_dump($variables);


function getFormVariables($html)
{
    $variables = [];

    $dom = new FluentDOM\Document();
    $dom->loadHTML($html);
    $xpath = new FluentDOM\Xpath($dom);

    $elements = $xpath->evaluate('//input');

    foreach ($elements as $element) {
        $nameAttr = $element->attributes->getNamedItem('name');
        $valueAttr = $element->attributes->getNamedItem('value');
        if ($nameAttr == null) {
            throw new \Exception("Input element is missing name: ".$element);
        }
        if ($valueAttr == null) {
            throw new \Exception("Input element is missing value: ".$element);
        }

        $variables[$nameAttr->nodeValue] = $valueAttr->nodeValue;
    }
    
    return $variables;
}
