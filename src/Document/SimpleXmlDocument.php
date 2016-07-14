<?php

declare(strict_types=1);

namespace Rs\XmlFilter\Document;

use Webmozart\Assert\Assert;

class SimpleXmlDocument extends BaseDocument implements Document
{
    public function loadString(string $doc) : Element
    {
        $document = simplexml_load_string($doc, SimpleXmlElement::class);

        Assert::isInstanceOf($document, Element::class);

        return $document;
    }

    public function loadDOM(\DOMNode $dom) : Element
    {
        $document = simplexml_import_dom($dom, SimpleXmlElement::class);

        Assert::isInstanceOf($document, Element::class);

        return $document;
    }
}
