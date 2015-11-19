<?php


namespace FCForms\FormElement;

class Hidden extends AbstractHiddenElement
{
    /**
     * @return string
     */
    public function getCSSClassName()
    {
        return 'Hidden';
    }

    /**
     * @param array $info
     * @return mixed|void
     */
    public function init(array $info)
    {
    }

    /**
     * @return mixed|string
     */
    public function render()
    {
        $output = "";
        $output .= sprintf(
            "<input type='hidden' name='%s' value='%s' />",
            $this->getFormName(),
            $this->getCurrentValue()
        );

        return $output;
    }
}
