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
function createInjector($data = [])
{
    $injector = new Injector();
    $injector->alias('FCForms\Form\DataStore', 'FCFormsTest\Form\ArrayDataStore');
    $injector->alias('Room11\HTTP\VariableMap', 'Room11\HTTP\VariableMap\ArrayVariableMap');
    $varMap = new ArrayVariableMap($data);
    $injector->share($varMap);

    $injector->alias('FCForms\FileFetcher', 'FCForms\FileFetcher\StubFileFetcher');
    $injector->share(new StubFileFetcher([]));

    $injector->share($injector);
    
    return $injector;
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
