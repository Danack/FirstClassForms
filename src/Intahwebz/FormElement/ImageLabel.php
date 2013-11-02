<?php


namespace Intahwebz\FormElement;


class ImageLabel extends AbstractElement {

    /**
     * @return string
     */
    function getClassName() {
        return "ImageLabel";
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
        $output .= "<img src='".$this->getCurrentValue()."' />";
        $output .= "</img>"; 
        $output .= "</div>";
        $output .= "</div>";

        return $output;
    }
}

