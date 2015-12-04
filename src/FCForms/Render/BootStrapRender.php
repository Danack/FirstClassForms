<?php

namespace FCForms\Render;

use Escape\Escape;
use FCForms\Form\Form;
use FCForms\FormElement\ElementPrototype;
use FCForms\FormElement\Element;
use FCForms\RenderException;
use FCForms\Render;
use FCForms\FormElement\CheckBox;
use FCForms\FormElement\Label;
use FCForms\FormElement\Password;
use FCForms\FormElement\RadioButton;
use FCForms\FormElement\Select;
use FCForms\FormElement\SubmitButton;
use FCForms\FormElement\Text;
use FCForms\FormElement\TextArea;
use FCForms\FormElement\Title;

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
        $this->renderCallables['FCForms\FormElement\RadioButton'] = [$this, 'renderRadioButton'];
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
                    Escape::escapeHTML($errorMessage)
                );
            }
        }

        return $helpText;
    }
    
    
    private function getHelpText(ElementPrototype $prototype)
    {
        $helpText = "";
        if ($prototype->helpText) {
            $helpText = sprintf(
                "<span id='helpBlock' class='help-block'>%s</span>",
                Escape::escapeHTML($prototype->helpText)
            );
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
                Escape::escapeHTMLAttribute($option),
                Escape::escapeHTML($selectedString),
                Escape::escapeHTMLAttribute($description)
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
            Escape::escapeHTMLAttribute($element->getFormName()),
            $optionText
        );

        return $output;
    }
    
        /**
     * @return mixed|string
     */
    public function renderPassword(Element $element, Password $prototype)
    {
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
            Escape::escapeHTML($element->getPrototype()->label),
            12 - $this->getLabelSpan(),
            Escape::escapeHTMLAttribute($prototype->getPlaceHolder())
        );

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
            Escape::escapeHTMLAttribute($element->getFormName()),
            Escape::escapeHTMLAttribute($element->getCurrentValue())
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
            Escape::escapeHTMLAttribute($element->getFormName()),
            Escape::escapeHTMLAttribute($element->getCurrentValue())
        );

        return $output;
    }

    /**
     * @param \FCForms\Form\Form $form
     * @return string
     */
    public function renderSubmitButton(Element $element, SubmitButton $prototype)
    {
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
            Escape::escapeHTML($prototype->text)
        );

        return $output;
    }
    
    
    /**
     * @return mixed|string
     */
    public function renderCheckBox(Element $element, CheckBox $prototype)
    {
        $errorText = $this->getErrorHTML($element);

        $errorClass = '';
        if ($element->hasError()) {
            $errorClass = 'has-error';
        }

        $checkedString = '';
        if ($element->getCurrentValue() == true) {
            $checkedString = "checked='checked'";
        }
        
        $helpText = $this->getHelpText($prototype);
        
        $html = <<< HTML
  <div class="form-group %s">
    <div class="col-sm-offset-%d col-sm-%d">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="%s" %s>%s
        </label>
      </div>
      %s
      %s
    </div>
  </div>
HTML;

        $output = sprintf(
            $html,
            $errorClass,
            $this->getLabelSpan(),
            12 - $this->getLabelSpan(),
            Escape::escapeHTMLAttribute($element->getFormName()),
            $checkedString,
            Escape::escapeHTML($prototype->getLabel()),
            $errorText,
            $helpText
        );

        return $output;
    }

    /**
     * @return mixed|string
     */
    public function renderLabel(Element $label, Label $prototype)
    {
        $html = <<< HTML
  <div class='form-group %s'>
     <div class="col-sm-offset-%d col-sm-%d">
        %s
     </div>
  </div>
HTML;

        $output = sprintf(
            $html,
            $prototype->getCSSClass(),
            $this->getLabelSpan(),
            12 - $this->getLabelSpan(),
            Escape::escapeHTML($label->getCurrentValue())
        );

        return $output;
    }

    /**
     * @return mixed|string
     */
    public function renderRadioButton(Element $element, RadioButton $prototype)
    {
        $radioHTML = <<< HTML
<div class="radio">
  <label>
    <input type="radio" name="%s" id="optionsRadios2" value="%s" %s />
    %s
  </label>
</div>
HTML;

        $errorText = $this->getErrorHTML($element);

        $errorClass = '';
        if ($element->hasError()) {
            $errorClass = 'has-error';
        }

        $radioText = '';
        foreach ($prototype->getOptionDescriptionMap() as $option => $description) {
            $checkedString = '';
            if ($option === $element->getCurrentValue()) {
                $checkedString = "checked";
            }

            $radioText .= sprintf(
                $radioHTML,
                Escape::escapeHTMLAttribute($element->getFormName()),
                Escape::escapeHTMLAttribute($option),
                $checkedString,
                Escape::escapeHTML($description)
            );
        }

        $html = <<< HTML
<div class="form-group %s">
    <label for="select" class="col-lg-%d control-label">%s</label>
    <div class="col-lg-%d">
      %s
      %s
    </div>
  </div>
HTML;

        $output = sprintf(
            $html,
            $errorClass,
            $this->getLabelSpan(),
            Escape::escapeHTMLAttribute($prototype->getLabel()),
            12 - $this->getLabelSpan(),
            $radioText,
            $errorText
        );

        return $output;
    }


    /**
     * @param Element $element
     * @param Title $prototype
     * @return string
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
            $prototype->getCSSClass(),
            Escape::escapeHTML($element->getCurrentValue())
        );

        return $output;
    }

    /**
     * @param Element $element
     * @param Text $prototype
     * @return string
     */
    public function renderText(Element $element, Text $prototype)
    {
        $errorText = $this->getErrorHTML($element);

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
            Escape::escapeHTMLAttribute($element->getFormName()),
            Escape::escapeHTMLAttribute($placeHolderText),
            Escape::escapeHTMLAttribute($element->getCurrentValue()),
            $errorText
        );

        return $output;
    }
    
    
    /**
     * @return mixed|string
     */
    public function renderTextArea(Element $element, TextArea $prototype)
    {
        $output = "";
        $errorText = $this->getErrorHTML($element);

        $errorClass = '';
        if ($element->hasError()) {
            $errorClass = 'has-error';
        }

        $placeHolderText = '';
        if ($prototype->getPlaceHolder() != null) {
            $placeHolderText .= sprintf(
                "placeholder='%s'",
                Escape::escapeHTMLAttribute($prototype->getPlaceHolder())
            );
        }

        $html = <<< HTML
<div class="form-group %s">
  <label for="textArea" class="col-sm-%d control-label">%s</label>
  <div class="col-lg-%d">
      <textarea class="form-control" rows="%d" cols="%d" id="textArea" %s>%s</textarea>
      %s
  </div>
</div>
HTML;

        $output .= sprintf(
            $html,
            Escape::escapeHTMLAttribute($errorClass),
            $this->getLabelSpan(),
            Escape::escapeHTML($prototype->label),
            12 - $this->getLabelSpan(),
            $prototype->getRows(),
            $prototype->getCols(),
            $placeHolderText,
            Escape::escapeHTML($element->getCurrentValue()),
            $errorText
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
     * @param Form $form
     * @return string
     * @throws \FCForms\FCFormsException
     * @throws \FCForms\Form\DataMissingException
     */
    public function render(Form $form)
    {
        $form->prepareToRender();
        
        $this->form = $form;
        $output = '';

        $formHTMLName = 'formname';
        $encodingString = "enctype='multipart/form-data'";
        
        $formIDString = '';
        $formID = $form->getHTMLID();
        if ($formID !== null) {
            $formIDString = sprintf(
                "id='%s'",
                Escape::escapeHTMLAttribute($formID)
            );
        }
        
        $output .= '<div class="well bs-component">';
        
        $output .= sprintf(
            "<form action='' method='post' name='%s' onsubmit='' class='form-horizontal %s' %s %s>\n",
            Escape::escapeHTMLAttribute($formHTMLName),
            $form->getStyleName(),
            $encodingString,
            $formIDString
        );
        
        $output .= "<fieldset>\n";

        if ($form->hasError() && ($formErrorMessage = $form->getErrorMessage())) {
            $html = <<< HTML
  <div class='form-group has-error'>
     <div class="col-sm-12">
        %s
     </div>
  </div>
HTML;
            $output .= sprintf(
                $html,
                $formErrorMessage
            );
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
        $output .= sprintf(
            "<input type='hidden' name='rowIDs' value='%s' />\n",
            Escape::escapeHTMLAttribute($rowIDs)
        );

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
