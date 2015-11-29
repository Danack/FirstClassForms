<?php

namespace FCForms\Render;

use FCForms\Form\Form;
use FCForms\FormElement\ElementPrototype;
use FCForms\FormElement\Element;
use FCForms\RenderException;

class BootStrapRender
{
    /**
     * @var \callable[]
     */
    private $renderCallables = [];

    /**
     *
     */
    public function __construct()
    {
        $this->renderCallables['FCForms\FormElement\Label'] = [$this, 'renderLabel'];
        $this->renderCallables['FCForms\FormElement\Title'] = [$this, 'renderTitle'];
        $this->renderCallables['FCForms\FormElement\CheckBox'] = [$this, 'renderCheckBox'];
        $this->renderCallables['FCForms\FormElement\Text'] = [$this, 'renderText'];
        $this->renderCallables['FCForms\FormElement\SubmitButton'] = [$this, 'renderSubmitButton'];
        $this->renderCallables['FCForms\FormElement\Hidden'] = [$this, 'renderHidden'];
        $this->renderCallables['FCForms\FormElement\CSRF'] = [$this, 'renderCSRF'];
    }

    public function getLabelSpan()
    {
        return 2;
    }

    /**
     * @param ElementPrototype $prototype
     * @return callable
     * @throws RenderException
     */
    protected function getRenderCallable(ElementPrototype $prototype)
    {
        $class = get_class($prototype);
        
        if (array_key_exists($class, $this->renderCallables) == false) {
            throw new RenderException("This renderer doesn't know how to render elements of type $class");
        }

        return $this->renderCallables[$class];
    }


    /**
     * Renders the form element
     * @return string
     */
    public function renderCSRF(Element $element)
    {
        $output = "";
        // TODO - this needs to be called somewhere....
        // $this->regenerateValue($elementInstance);
        $errorMessages = $element->getErrorMessages();
        if (count($errorMessages) > 0) {
            $output .= "<div class='errorMessage'>\n";
            foreach ($errorMessages as $errorMessage) {
                $output .= $errorMessage;
            }
            $output .= "</div>\n";
        }

        $output .= sprintf(
            "<input type='hidden' name='%s' value='%s' />\n",
            $element->getFormName(),
            $element->getCurrentValue()
        );

        return $output;
    }

    /**
     * @return mixed|string
     */
    public function renderHidden(Element $element)
    {
        $output = "";
        $output .= sprintf(
            "<input type='hidden' name='%s' value='%s' />\n",
            $element->getFormName(),
            $element->getCurrentValue()
        );

        return $output;
    }

    /**
     * @param \FCForms\Form\Form $form
     * @return string
     */
    public function renderSubmitButton(Element $element)
    {
        /** @var  $prototype \FCForms\FormElement\SubmitButton */
        $prototype = $element->getPrototype();

        $output = "";
        $output .= "<div class='row-fluid'>";
        $offsetClass = 'span' . ($this->getLabelSpan());
        $spanClass = 'span' . (12 - $this->getLabelSpan());

        $output .= "<div class='$offsetClass'>\n";
        $output .= "&nbsp;\n";
        $output .= "</div>\n";
        $output .= "<div class='$spanClass'>\n";
        $output .= sprintf(
            "<input type='submit' name='%s' value='%s' />\n",
            $element->getFormName(),
            $prototype->text
        );

        $output .= "</div>\n";
        $output .= "</div>\n";

        return $output;
    }
    
    
    /**
     * @return mixed|string
     */
    public function renderCheckBox(Element $element)
    {
        /** @var $prototype \FCForms\FormElement\CheckBox */
        $prototype = $element->getPrototype();
        
        $output = "<div class='row-fluid'>\n";
        $labelSpan = "span" . $this->getLabelSpan();
        $remainingSpan = "span" . (12 - $this->getLabelSpan());

        if ($element->getPrototype()->label != null) {
            $output .= sprintf(
                "<label class='%s' for='%s'>%s</label>",
                $labelSpan,
                $element->getFormName(),
                $prototype->label
            );
        }

        $checked = '';
        if ($element->getCurrentValue() == true) {
            $checked = "checked='checked'";
        }

        $output .= "<div class='$remainingSpan'>\n";
        $output .= sprintf(
            "<input type='checkbox' name='%s' value='1' %s />\n",
            $element->getFormName(),
            $checked
        );
        $output .= "</div>\n";
        $output .= "</div>\n";

        return $output;
    }

    /**
     * @return mixed|string
     */
    public function renderLabel(Element $label)
    {
        $output = "<div class='row-fluid'>\n";
        $output .= "<div class='".$label->getCSSClassName()." span12'>\n";
        $output .= $label->getCurrentValue();
        $output .= "</div>\n";
        $output .= "</div>\n";

        return $output;
    }
    
        /**
     * @return mixed|string
     */
    public function renderTitle(Element $elementInstance)
    {
        $output = "<div class='row-fluid'>\n";
        $output .= "<legend class='span12'>\n";
        $output .= $elementInstance->getCurrentValue();
        $output .= "</legend>\n";
        $output .= "</div>\n";

        return $output;
    }

    /**
     * @return mixed|string
     */
    public function renderText(Element $element)
    {
        /** @var $prototype \FCForms\FormElement\Text */
        $prototype = $element->getPrototype();

        $output = "";
        $errorMessages = $element->getErrorMessages();

        if (count($errorMessages) > 0) {
            $output .= "<div class='row-fluid'>\n";
            $output .= "<div class='errorMessage span12'>\n";
            foreach ($errorMessages as $errorMessage) {
                $output .= $errorMessage;
            }
            $output .= "</div>\n";
            $output .= "</div>\n";
        }

        $output .= "<div class='row-fluid'>\n";
        $remainingSpan = 'span12';

        if ($prototype->label !== null) {
            $labelSpan = "span" . $this->getLabelSpan();
            $remainingSpan = "span" . (12 - $this->getLabelSpan());
            $output .= sprintf(
                "<label class='%s' for='%s'>%s</label>\n",
                $labelSpan,
                $element->getFormName(),
                $prototype->label
            );
        }

        $output .= "<div class='$remainingSpan'>\n";
        
        $placeHolderText = "";
        $placeHolder = $prototype->getPlaceHolder();
        if ($placeHolder != null) {
            $placeHolderText .= "placeholder='".$placeHolder."'";
        }

        $output .= sprintf(
            "<input type='text' name='%s' size='80' value='%s' style='width: 100%%;' %s />\n",
            $element->getFormName(),
            htmlentities($element->getCurrentValue(), ENT_QUOTES),
            $placeHolderText
        );

        $output .= "</div>\n";
        $output .= "</div>\n";

        return $output;
    }
    
    
    /**
     * @param Element $element
     * @return mixed
     * @throws RenderException
     */
    public function renderElement(Element $element)
    {
        $prototype = $element->getPrototype();
        $callable = $this->getRenderCallable($prototype);

        return $callable($element);
    }

    /**
     * @return string
     */
    public function render(Form $form)
    {
        $form->prepareToRender();
        
        $this->form = $form;
        $output = '';

        $form->getFormErrorMessage();

        $formHTMLName = 'vbform';
        $encodingString = "enctype='multipart/form-data'";
        
        $formIDString = '';
        $formID = $form->getHTMLID();
        if ($formID !== null) {
            $formIDString = sprintf("id='%s'", $formID);
        }

        $output .= sprintf(
            "<form action='' method='post' name='%s' onsubmit='' class='well %s' %s %s>",
            $formHTMLName,
            $form->getStyleName(),
            $encodingString,
            $formIDString
        );

        $output .= "<div class='row-fluid'>\n";
        $output .= "<div class='span12' style='padding-left: 20px'>\n";

        foreach ($form->startElements as $element) {
            $output .= $this->renderElement($element);
            $output .= "\n";
        }

        foreach ($form->rowElementsArray as $rowElements) {
            foreach ($rowElements as $element) {
                $output .= $this->render($element);
                $output .= "\n";
            }
        }

        $rowIDs = implode(',', $form->rowIDs);
        $output .= "<input type='hidden' name='rowIDs' value='".$rowIDs."' />";

        foreach ($form->endElements as $element) {
            $output .= $this->renderElement($element);
            $output .= "\n";
        }

        $output .= "</div>\n";
        $output .= "</div>";
        $output .= "</form>\n";

        return $output;
    }
}
