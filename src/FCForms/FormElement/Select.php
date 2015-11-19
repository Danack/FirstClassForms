<?php


namespace FCForms\FormElement;

class Select extends AbstractElement
{
    private $optionDescriptionMap = array();

    public function init(array $info)
    {
        //$values
        //$descriptions
        $this->optionDescriptionMap = $info['options'];
    }

    /**
     * @return string
     */
    public function getCSSClassName()
    {
        return 'Select';
    }

    /**
     * @return mixed|string
     */
    public function render()
    {
        $output = "<div class='row-fluid'>";
        $labelSpan = "span" . $this->form->getLabelSpan();

        $remainingSpan = "span" . (12 - $this->form->getLabelSpan());

        if ($this->label != null) {
            $output .= "<label class='$labelSpan' for='" . $this->getFormName() . "'>" . $this->label . "</label>";
        }

        $output .= "<div class='$remainingSpan'>";

        $output .= "<select name='" . $this->getFormName() . "'>";
        
        foreach ($this->optionDescriptionMap as $option => $description) {
            $selectedString = '';
            if ($option === $this->getCurrentValue()) {
                $selectedString = "selected='selected'";
            }
            
            $output .= sprintf(
                "<option value='%s' %s >%s</option>",
                safeText($option),
                $selectedString,
                safeText($description)
            );
        }

        $output .= "</select>";

        $output .= "</div>";
        $output .= "</div>";

        return $output;
    }
}
