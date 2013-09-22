<?php


namespace Intahwebz\FormElement;


class Hidden extends AbstractElement {

    /**
     * @return string
     */
    function getClassName() {
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

