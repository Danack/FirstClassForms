<?php


namespace FCFormsTest;

use FCFormsTest\BaseTestCase;
use Auryn\Injector;
use FCForms\Validator\StringLengthOrNull;

class StringLengthOrNullTest extends BaseTestCase
{
    public function listTests()
    {
        return [
            ["short", false],
            ["thisistoolongastring", false],
            ["good length", true],
            [null, true],
        ];
    }
    
    /**
     * @dataProvider listTests
     */
    public function testStringLengthOrNull($testValue, $expectedResult)
    {
        $options = [
            'min'      => 6,
            'max'      => 12,
        ];

        $validator = new StringLengthOrNull($options);
        $valid = $validator->isValid($testValue);
        $this->assertEquals($expectedResult, $valid, "Wrong result for $testValue");
    }
}
