<?php

namespace FCForms\FormElement;

interface ElementValidator
{
    /**
     * Validator that does more complicated analysis on an element rather than just looking at the
     * current value e.g. CSRF compares submitted value with value stored in session.
     * @param AbstractElement $element
     * @return mixed
     */
    public function isValidElement(AbstractElement $element);
}
