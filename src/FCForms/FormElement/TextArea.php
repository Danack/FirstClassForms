<?php


namespace FCForms\FormElement;

use FCForms\Form\Form;

class TextArea extends AbstractElementPrototype
{
    private $rows = 8;

    private $cols = null;

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
     * @return string
     */
    public function getCSSClassName()
    {
        return "InputText";
    }

    /**
     * @return mixed|string
     */
    public function render(Form $form, Element $elementInstance)
    {
        $output = "";
        $errorMessages = $elementInstance->getErrorMessages();
        if (count($errorMessages) > 0) {
            $output .= "<div class='row-fluid'>";
            $output .= "<div class='errorMessage span12'>";
            foreach ($errorMessages as $errorMessage) {
                $output .= $errorMessage;
            }
            $output .= "</div>";
            $output .= "</div>";
        }

        $output .= "<div class='row-fluid'>";
        $remainingSpan = 'span12';

        if ($this->label !== null) {
            $labelSpan = "span" . $form->getLabelSpan();
            $remainingSpan = "span" . (12 - $form->getLabelSpan());
            $output .= sprintf(
                "<label class='%s' for='%s'>%s</label>",
                $labelSpan,
                $elementInstance->getFormName(),
                $this->label
            );
        }

        $output .= "<div class='$remainingSpan'>";
        $output .= "<textarea type='text' name='".$elementInstance->getFormName(). "'";

        if ($this->placeHolder != null) {
            $output .= "placeholder='".$this->placeHolder."'";
        }

        $output .= "rows='".$this->rows."'";

        if ($this->cols != null) {
            $output .= "cols='".$this->cols."'";
        }
        else {
            $output .= "style='width: 100%'";
        }
        $output .= "/>";
        $output .= htmlentities($elementInstance->getCurrentValue());
        $output .= "</textarea>";
        $output .= "</div>";
        $output .= "</div>";

        return $output;
    }
}
