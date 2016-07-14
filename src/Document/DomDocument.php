<?php

declare(strict_types=1);

namespace Rs\XmlFilter\Document;

use Webmozart\Assert\Assert;

class DomDocument extends BaseDocument implements Document
{
    public function loadString(string $doc) : Element
    {
        $document = new \DOMDocument();
        $document->registerNodeClass(\DOMElement::class, DomElement::class);
        $loaded = $document->loadXML($doc);

        Assert::true($loaded, sprintf('Unable to load the xml string "%s"', $doc));

        return $document->childNodes->item(0);
    }

    public function loadDOM(\DOMNode $dom) : Element
    {
        if ($dom instanceof \DOMDocument) {
            return $this->loadString($dom->saveXML());
        }

        return $this->loadString($dom->textContent);
    }
}
