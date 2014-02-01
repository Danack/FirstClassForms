<?php


namespace Intahwebz\FormElement;


class Hidden extends AbstractHiddenElement {

    /**
     * @return string
     */
    function getCSSClassName() {
        return 'Hidden';
    }

    /**
     * @param array $info
     * @return mixed|void
     */
    function init(array $info) {
    }

    /**
     * @return mixed|string
     */
    function render() {
        $output = "";
        $output .= "<input type='hidden' name='" . $this->getFormName() . "' value='" . $this->getCurrentValue() . "' />";

        return $output;
    }
    
    
}

