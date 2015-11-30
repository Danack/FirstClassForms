<?php

namespace FCForms;

interface DataStore
{
    public function getValue($name, $default, $clearOnRead);

    public function setValue($name, $data);
}
