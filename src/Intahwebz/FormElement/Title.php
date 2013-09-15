<?php


namespace Intahwebz\FormElement;


class Title extends AbstractElement {

    /**
     * @return string
     */
    function getClassName() {
        return "Title";
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
        $output = "<div class='row-fluid'>";
        $output .= "<legend class='span12'>";
        $output .= $this->currentValue;
        $output .= "</legend>";
        $output .= "</div>";

        return $output;
    }
}

