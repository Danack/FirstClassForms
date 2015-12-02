<?php

namespace FCFormsTest\Controller;

use FCFormsTest\ExampleForms\ListExamplesForm;

class Example
{
    public function index(ListExamplesForm $listForm)
    {
        $listForm->createFromData([]);
        return \Tier\getRenderTemplateTier('pages/index');
    }
    
    public function listExample(ListExamplesForm $listForm)
    {
        $listForm->createFromData([]);
        return \Tier\getRenderTemplateTier('pages/listExample');
    }
}
