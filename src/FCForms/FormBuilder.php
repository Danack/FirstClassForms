<?php

namespace FCForms;

use FCForms\Form\Form;

interface FormBuilder
{
    public function buildFormPrototypeFromDefinition(Form $form, array $definition);
}