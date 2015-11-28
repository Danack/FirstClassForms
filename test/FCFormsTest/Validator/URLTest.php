<?php


namespace FCFormsTest\Validator;

use FCFormsTest\BaseTestCase;
use Auryn\Injector;
use FCForms\Validator\URL;

class CSRFTest extends BaseTestCase
{
    public function listURLS()
    {
        return [
            ["http://www.google.com", true],
            ["notaurl", false],
        ];
    }
    
    /**
     * @dataProvider listURLS
     */
    public function testURLs($testValue, $expectedResult)
    {
        $validator = new URL();
        $valid = $validator->isValid($testValue);
        $this->assertEquals($expectedResult, $valid, "Wrong result for $testValue");
    }
}
