<?php


namespace FCFormsTest\Model;

class Debug
{
    private $lines = [];
    
    public function add($line)
    {
        $this->lines[] = $line;
    }
    
    public function render()
    {
        return implode("<br/>", $this->lines);
    }
}
