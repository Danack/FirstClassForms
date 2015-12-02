<?php

namespace FCForms\FormElement;

class TextArea extends ElementPrototype
{
    private $rows = 3;

    private $cols = 80;

    /**
     * @param array $info
     * @return mixed|void
     */
    public function init(array $info)
    {
        if (array_key_exists('rows', $info) == true) {
            $this->rows = intval($info['rows']);
        }
        if (array_key_exists('cols', $info) == true) {
            $this->cols = intval($info['cols']);
        }
    }

    /**
     * @return int
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @return null
     */
    public function getCols()
    {
        return $this->cols;
    }

    /**
     * @return string
     */
    public function getPrototypeCSSClass()
    {
        return "fc_textarea";
    }
}
