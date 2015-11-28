<?php


namespace FCForms\FormElement;

use FCForms\Form\Form;

class Select extends AbstractElementPrototype
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
    public function render(Form $form, Element $elementInstance)
    {
        $output = "<div class='row-fluid'>";
        $labelSpan = "span" . $form->getLabelSpan();

        $remainingSpan = "span" . (12 - $form->getLabelSpan());

        if ($this->label != null) {
            $output .= "<label class='$labelSpan' for='".$elementInstance->getFormName()."'>".$this->label."</label>";
        }

        $output .= "<div class='$remainingSpan'>";

        $output .= "<select name='" . $elementInstance->getFormName() . "'>";
        
        foreach ($this->optionDescriptionMap as $option => $description) {
            $selectedString = '';
            if ($option === $elementInstance->getCurrentValue()) {
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
