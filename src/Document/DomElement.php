<?php

declare(strict_types=1);

namespace Rs\XmlFilter\Document;

class DomElement extends \DOMElement implements Element
{
    public function find(string $path) : array
    {
        $xpath = new \DOMXpath($this->ownerDocument);

        $nodes = $xpath->query($path, $this);

        $result = [];

        foreach ($nodes as $node) {
            $result[] = $node;
        }

        return $result;
    }

    public function __toString()
    {
        return (string) $this->textContent;
    }

    public function attribute($name) : string
    {
        return $this->getAttribute($name);
    }
}
