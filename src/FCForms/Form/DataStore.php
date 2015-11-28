<?php

namespace FCForms\Form;

interface DataStore
{
    public function getValue($name, $default, $clearOnRead);

    public function setValue($name, $data);
}
