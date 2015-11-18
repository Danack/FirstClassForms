<?php


namespace Intahwebz\FormElement;


class Link extends AbstractElement {

    /**
     * @return string
     */
    function getCSSClassName() {
        return "Link";
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
        //$output .= $this->getCurrentValue();
        $output .= "This is meant to be a link";
        $output .= "</div>";
        $output .= "</div>";

        return $output;
    }
}

