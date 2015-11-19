<?php

namespace FCForms\Form;

interface DataStore
{
    public function getData($name, $default, $clearOnRead);

    public function storeData($name, $data);
}
