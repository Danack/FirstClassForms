<?php


namespace FCFormsTest;

use FCFormsTest\BaseTestCase;
use FCForms\Validator\FileSize;
use FCForms\UploadedFile;

class FileSizeTest extends BaseTestCase
{
    public function setup()
    {
        parent::setup();
    }

    public function teardown()
    {
        parent::teardown();
    }
    
    public function listTests()
    {
        return [
            [__FILE__, 0, true],
            [__FILE__, 150, false],
            [__FILE__, -150, false],
        ];
    }

    /**
     * @dataProvider listTests
     */
    public function testFileSize($filename, $offset, $expectedResult)
    {
        $options = [
            'minSize' => (filesize($filename) - 100 + $offset),
            'maxSize' => (filesize($filename) + 100 + $offset),
        ];

        $uploadedFile = new UploadedFile("Whatever", __FILE__, filesize(__FILE__));
        $validator = new FileSize($options);
        $valid = $validator->isValid($uploadedFile);
        $this->assertEquals($expectedResult, $valid);
    }

    public function testWrongTypeError()
    {
        $options = [
            'minSize' => (filesize(__FILE__) - 100),
            'maxSize' => (filesize(__FILE__) + 100),
        ];
        $validator = new FileSize($options);
        $this->setExpectedException('FCForms\FCFormsException');
        $validator->isValid(new \StdClass());
    }
}
