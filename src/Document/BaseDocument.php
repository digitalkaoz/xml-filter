<?php

declare(strict_types=1);

namespace Rs\XmlFilter\Document;

use Webmozart\Assert\Assert;

abstract class BaseDocument
{
    public function loadFile(string $file) : Element
    {
        Assert::readable($file);

        return $this->loadString(file_get_contents($file));
    }

    public function loadStream($stream) : Element
    {
        Assert::resource($stream);

        @rewind($stream);

        return $this->loadString(stream_get_contents($stream));
    }

    abstract public function loadString(string $doc) : Element;

    abstract public function loadDOM(\DOMNode $dom) : Element;

    public function loadSimpleXml(\SimpleXMLElement $element) : Element
    {
        return $this->loadString($element->asXML());
    }

    public function load($content) : Element
    {
        if ($content instanceof \SimpleXMLElement) {
            return $this->loadSimpleXml($content);
        } elseif ($content instanceof \DOMNode) {
            return $this->loadDOM($content);
        } elseif (is_resource($content)) {
            return $this->loadStream($content);
        } elseif (is_file($content)) {
            return $this->loadFile($content);
        }

        return $this->loadString($content);
    }
}
