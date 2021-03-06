<?php

namespace FCForms\Bridge;

use FCForms\Escaper;
use Zend\Escaper\Escaper as ZendEscape;
use FCForms\EscapeException;

class ZendEscaperBridge implements Escaper
{
    /** @var ZendEscape */
    private $zendEscape;
    
    public function __construct(ZendEscape $zendEscape)
    {
        $this->zendEscape = $zendEscape;
    }
    
    public function escapeHTML($string)
    {
        if ($string === null) {
            return '';
        }
        if (is_object($string) == true) {
            if (method_exists($string, '__toString') == false) {
                throw EscapeException::fromBadObject($string);
            }
            $string = (string)$string;
        }
        if (is_array($string) == true) {
            throw EscapeException::fromBadArray();
        }

        return $this->zendEscape->escapeHtml($string);
    }

    public function escapeHTMLAttribute($string)
    {
        if ($string === null) {
            return '';
        }
        if (is_object($string) == true) {
            if (method_exists($string, '__toString') == false) {
                throw EscapeException::fromBadObject($string);
            }
            $string = (string)$string;
        }
        if (is_array($string) == true) {
            throw EscapeException::fromBadArray();
        }

        return $this->zendEscape->escapeHtmlAttr($string);
    }

    public function escapeJavascript($string)
    {
        if ($string === null) {
            return '';
        }
        if (is_object($string) == true) {
            if (method_exists($string, '__toString') == false) {
                throw EscapeException::fromBadObject($string);
            }
            $string = (string)$string;
        }
        if (is_array($string) == true) {
            throw EscapeException::fromBadArray();
        }

        return $this->zendEscape->escapeJs($string);
    }

    public function escapeCSS($string)
    {
        if ($string === null) {
            return '';
        }
        if (is_object($string) == true) {
            if (method_exists($string, '__toString') == false) {
                throw EscapeException::fromBadObject($string);
            }
            $string = (string)$string;
        }
        if (is_array($string) == true) {
            throw EscapeException::fromBadArray();
        }

        return $this->zendEscape->escapeCss($string);
    }

    public function escapeURLComponent($string)
    {
        if ($string === null) {
            return '';
        }
        if (is_object($string) == true) {
            if (method_exists($string, '__toString') == false) {
                throw EscapeException::fromBadObject($string);
            }
            $string = (string)$string;
        }
        if (is_array($string) == true) {
            throw EscapeException::fromBadArray();
        }

        return $this->zendEscape->escapeUrl($string);
    }
}
