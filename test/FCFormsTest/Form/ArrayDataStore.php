<?php

namespace FCFormsTest\Form;

use FCForms\DataStore;

class ArrayDataStore implements \FCForms\DataStore
{
    private $storage = [];
    
    public function getValue($name, $default, $clearOnRead)
    {
        if (!array_key_exists($name, $this->storage)) {
            return $default;
        }

        $value = $this->storage[$name];

        if ($clearOnRead) {
            unset($this->storage[$name]);
        }
        
        return $value;
    }

    public function setValue($name, $data)
    {
        $this->storage[$name] = $data;
    }
}
