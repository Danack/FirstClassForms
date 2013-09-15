<?php


namespace Intahwebz\FormElement;


class Label extends AbstractElement {

    /**
     * @return string
     */
    function getClassName() {
        return "Label";
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
        $output .= "<div class='" . $this->getStyleName() . " span12'>";
        $output .= $this->currentValue;
        $output .= "</div>";
        $output .= "</div>";

        return $output;
    }
}

