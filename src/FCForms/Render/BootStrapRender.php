<?php

namespace FCForms\Render;

use FCForms\Form\Form;
use FCForms\FormElement\ElementPrototype;
use FCForms\FormElement\Element;
use FCForms\RenderException;
use FCForms\Render;
use FCForms\FormElement\Password;
use FCForms\FormElement\Select;
use FCForms\FormElement\Text;
use FCForms\FormElement\Title;

// @TODO - <span class="help-block"></span>
class BootStrapRender implements Render
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
        $this->renderCallables['FCForms\FormElement\CheckBox'] = [$this, 'renderCheckBox'];
        $this->renderCallables['FCForms\FormElement\CSRF'] = [$this, 'renderCSRF'];
        $this->renderCallables['FCForms\FormElement\Hidden'] = [$this, 'renderHidden'];
        $this->renderCallables['FCForms\FormElement\Label'] = [$this, 'renderLabel'];
        $this->renderCallables['FCForms\FormElement\Password'] = [$this, 'renderPassword'];
        $this->renderCallables['FCForms\FormElement\Select'] = [$this, 'renderSelect'];
        $this->renderCallables['FCForms\FormElement\SubmitButton'] = [$this, 'renderSubmitButton'];
        $this->renderCallables['FCForms\FormElement\Text'] = [$this, 'renderText'];
        $this->renderCallables['FCForms\FormElement\TextArea'] = [$this, 'renderTextArea'];
        
        
        $this->renderCallables['FCForms\FormElement\Title'] = [$this, 'renderTitle'];
    }

    public function getLabelSpan()
    {
        return 3;
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

    private function getErrorHTML(Element $element)
    {
        $errorMessages = $element->getErrorMessages();
        
        $helpText = "";

        if (count($errorMessages) > 0) {
            //.has-warning, .has-error, or .has-success
            foreach ($errorMessages as $errorMessage) {
                $helpText .= sprintf(
                    "<span id='helpBlock2' class='help-block'>%s</span>",
                    $errorMessage
                );
            }
        }

        return $helpText;
    }
    
    
    /**
     * @return mixed|string
     */
    public function renderSelect(Element $element, Select $prototype)
    {
        $optionText = '';
        foreach ($prototype->getOptionDescriptionMap() as $option => $description) {
            $selectedString = '';
            if ($option === $element->getCurrentValue()) {
                $selectedString = "selected='selected'";
            }
            
            $optionText .= sprintf(
                "<option value='%s' %s >%s</option>\n",
                htmlentities($option),
                $selectedString,
                htmlentities($description)
            );
        }

        //multiple=""

        $html = <<< HTML
<div class="form-group">
    <label for="select" class="col-lg-%d control-label">%s</label>
    <div class="col-lg-%d">
      <select class="form-control" id="select" name="%s">
          %s
      </select>
    </div>
  </div>
HTML;

        $output = sprintf(
            $html,
            $this->getLabelSpan(),
            $prototype->getLabel(),
            12 - $this->getLabelSpan(),
            $element->getFormName(),
            $optionText
        );

        return $output;
    }
    
        /**
     * @return mixed|string
     */
    public function renderPassword(Element $element, Password $prototype)
    {
//        $output = "";
//        if (count($element->errorMessages) > 0) {
//            $output .= "<div class='row-fluid'>";
//            $output .= "<div class='errorMessage span12'>";
//            foreach ($element->errorMessages as $errorMessage) {
//                $output .= $errorMessage;
//            }
//            $output .= "</div>";
//            $output .= "</div>";
//        }

//        $output .= "<div class='row-fluid'>";
//        $remainingSpan = 'span12';

//        if ($element->getPrototype()->label !== null) {
//            $labelSpan = "span" . $this->getLabelSpan();
//            $remainingSpan = "span" . (12 - $this->getLabelSpan());
//            $output .= sprintf(
//                "<label class='%s' for='%s'>%s</label>",
//                $labelSpan,
//                $element->getFormName(),
//                
//            );
//        }
        
        $html = <<< HTML
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-%d control-label">%s</label>
    <div class="col-sm-%d">
      <input type="password" class="form-control" id="inputPassword3" placeholder="%s">
    </div>
  </div>
HTML;

        $output = sprintf(
            $html,
            $this->getLabelSpan(),
            $element->getPrototype()->label,
            12 - $this->getLabelSpan(),
            $prototype->getPlaceHolder()
        );
        
//
//        $output .= "<div class='$remainingSpan'>";
//
//        $output .= sprintf(
//            "<input type='password' name='%s' size='80' value='%s' placeholder='Password' %s />",
//            $element->getFormName(),
//            htmlentities($element->getCurrentValue()),
//            "style='width: 100%;'"
//        );
//
//        $output .= "</div>";
//        $output .= "</div>";

        return $output;
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

        $html = <<< HTML
  <div class="form-group">
    <div class="col-sm-offset-%d col-sm-%d">
      <button type="submit" class="btn btn-primary">%s</button>
    </div>
  </div>
HTML;

        $output = sprintf(
            $html,
            $this->getLabelSpan(),
            12 - $this->getLabelSpan(),
            $prototype->text
        );
        //$element->getFormName(),

        return $output;
    }
    
    
    /**
     * @return mixed|string
     */
    public function renderCheckBox(Element $element)
    {
        /** @var $prototype \FCForms\FormElement\CheckBox */
        $prototype = $element->getPrototype();
        $checkedString = '';
        if ($element->getCurrentValue() == true) {
            $checkedString = "checked='checked'";
        }
        
        $html = <<< HTML
  <div class="form-group">
    <div class="col-sm-offset-%d col-sm-%d">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="%s" %s>%s
        </label>
      </div>
    </div>
  </div>
HTML;

        $output = sprintf(
            $html,
            $this->getLabelSpan(),
            12 - $this->getLabelSpan(),
            $element->getFormName(),
            $checkedString,
            $prototype->getLabel()
        );

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
    public function renderTitle(Element $element, Title $prototype)
    {
        $html = <<< HTML
        <legend class='%s'>
            %s
        </legend>
HTML;

        $output = sprintf(
            $html,
            $prototype->getPrototypeCSSClass(),
            $element->getCurrentValue()
        );

        return $output;
    }

    /**
     * @return mixed|string
     */
    public function renderText(Element $element, Text $prototype)
    {
        $helpText = $this->getErrorHTML($element);

        $errorClass = '';
        if ($element->hasError()) {
            $errorClass = 'has-error';
        }
    
        $placeHolderText = "";
        $placeHolder = $prototype->getPlaceHolder();
        if ($placeHolder != null) {
            $placeHolderText .= $placeHolder;
        }

        $html = <<< HTML
  <div class="form-group $errorClass">
    <label for="inputEmail3" class="col-sm-%d control-label">%s</label>
    <div class="col-sm-%d">
      <input type="text" class="form-control" id="inputEmail3" name="%s" placeholder="%s" value="%s">
      %s
    </div>
  </div>
HTML;

        $output = sprintf(
            $html,
            $this->getLabelSpan(),
            $prototype->label,
            12 - $this->getLabelSpan(),
            $element->getFormName(),
            $placeHolderText,
            htmlentities($element->getCurrentValue(), ENT_QUOTES),
            $helpText
        );

        return $output;
    }
    
    
    /**
     * @return mixed|string
     */
    public function renderTextArea(Element $element)
    {
        $output = "";
        $errorMessages = $element->getErrorMessages();
        if (count($errorMessages) > 0) {
            $output .= "<div class='row-fluid'>";
            $output .= "<div class='errorMessage span12'>";
            foreach ($errorMessages as $errorMessage) {
                $output .= $errorMessage;
            }
            $output .= "</div>";
            $output .= "</div>";
        }

//        $output .= "<div class='row-fluid'>";
//        $remainingSpan = 'span12';
//
//        if ($this->label !== null) {
//            $labelSpan = "span" . $form->getLabelSpan();
//            $remainingSpan = "span" . (12 - $form->getLabelSpan());
//            $output .= sprintf(
//                "<label class='%s' for='%s'>%s</label>",
//                $labelSpan,
//                $elementInstance->getFormName(),
//                $this->label
//            );
//        }
//        $output .= "<div class='$remainingSpan'>";
//        $output .= "<textarea type='text' name='".$elementInstance->getFormName(). "'";

        /** @var $prototype \FCForms\FormElement\TextArea */
        $prototype = $element->getPrototype();

        
        $placeHolderText = '';
        if ($prototype->getPlaceHolder() != null) {
            $placeHolderText .= "placeholder='".$prototype->getPlaceHolder()."'";
        }

//        $output .= "rows='".$this->rows."'";
//
//        if ($this->cols != null) {
//            $output .= "cols='".$this->cols."'";
//        }
//        else {
//            $output .= "style='width: 100%'";
//        }
//        $output .= "/>";
//        $output .= htmlentities($elementInstance->getCurrentValue());
//        $output .= "</textarea>";
//        $output .= "</div>";
//        $output .= "</div>";

        $html = <<< HTML
<div class="form-group">
  <label for="textArea" class="col-sm-%d control-label">%s</label>
  <div class="col-lg-%d">
      <textarea class="form-control" rows="%d" cols="%d" id="textArea" %s>%s</textarea>
  </div>
</div>
HTML;

        $output .= sprintf(
            $html,
            $this->getLabelSpan(),
            $prototype->label,
            12 - $this->getLabelSpan(),
            $prototype->getRows(),
            $prototype->getCols(),
            $placeHolderText,
            $element->getCurrentValue()
        );

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

        return $callable($element, $prototype);
    }

    /**
     * @return string
     */
    public function render(Form $form)
    {
        $form->prepareToRender();
        
        $this->form = $form;
        $output = '';

        $form->getErrorMessage();

        $formHTMLName = 'formname';
        $encodingString = "enctype='multipart/form-data'";
        
        $formIDString = '';
        $formID = $form->getHTMLID();
        if ($formID !== null) {
            $formIDString = sprintf("id='%s'", $formID);
        }
        
        $output .= '<div class="well bs-component">';
        
        $output .= sprintf(
            "<form action='' method='post' name='%s' onsubmit='' class='form-horizontal %s' %s %s>\n",
            $formHTMLName,
            $form->getStyleName(),
            $encodingString,
            $formIDString
        );
        
        $output .= "<fieldset>\n";

        if ($form->hasError() && ($formErrorMessage = $form->getErrorMessage())) {
            $output .= "<div class='row-fluid'>\n";
            $output .= "<div class='span12' style='padding-left: 20px'>\n";
            $output .= $formErrorMessage."\n";
            $output .= "</div>\n";
            $output .= "</div>\n";
        }

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
        $output .= "<input type='hidden' name='rowIDs' value='".$rowIDs."' />\n";

        foreach ($form->endElements as $element) {
            $output .= $this->renderElement($element);
            $output .= "\n";
        }

        $output .= "</fieldset>\n";
        $output .= "</form>\n";
        $output .= '</div>';

        return $output;
    }
}
