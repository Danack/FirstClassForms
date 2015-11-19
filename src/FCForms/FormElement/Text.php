<?php


namespace FCForms\FormElement;

class Text extends AbstractElement
{
    /**
     * @param array $info
     * @return mixed|void
     */
    public function init(array $info)
    {
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
    public function render()
    {
        $output = "";
        if (count($this->errorMessages) > 0) {
            $output .= "<div class='row-fluid'>";
            $output .= "<div class='errorMessage span12'>";
            foreach ($this->errorMessages as $errorMessage) {
                $output .= $errorMessage;
            }
            $output .= "</div>";
            $output .= "</div>";
        }

        $output .= "<div class='row-fluid'>";
        $remainingSpan = 'span12';

        if ($this->label !== null) {
            $labelSpan = "span" . $this->form->getLabelSpan();
            $remainingSpan = "span" . (12 - $this->form->getLabelSpan());
            $output .= sprintf(
                "<label class='%s' for='%s'>%s</label>",
                $labelSpan,
                $this->getFormName(),
                $this->label
            );
        }

        $output .= "<div class='$remainingSpan'>";
        $output .= sprintf(
            "<input type='text' name='%s' size='80' value='%s'",
            $this->getFormName(),
            htmlentities($this->getCurrentValue(), ENT_QUOTES)
        );

        if ($this->placeHolder != null) {
            $output .= "placeholder='".$this->placeHolder."'";
        }

        $output .= "style='width: 100%;' />";

        $output .= "</div>";
        $output .= "</div>";

        return $output;
    }
}
