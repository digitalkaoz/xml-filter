<?php

declare(strict_types=1);

namespace Rs\XmlFilter\Document;

interface Document
{
    /**
     * loads a file.
     *
     * @param string $file
     *
     * @return Element
     */
    public function loadFile(string $file) : Element;

    /**
     * loads a stream.
     *
     * @param resource $stream
     *
     * @return Element
     */
    public function loadStream($stream) : Element;

    /**
     * loads a string.
     *
     * @param string $doc
     *
     * @return Element
     */
    public function loadString(string $doc) : Element;

    /**
     * loads a \DOMNode.
     *
     * @param \DOMNode $dom
     *
     * @return Element
     */
    public function loadDOM(\DOMNode $dom) : Element;

    /**
     * loads a \SimpleXmlElement.
     *
     * @param \SimpleXMLElement $element
     *
     * @return Element
     */
    public function loadSimpleXml(\SimpleXMLElement $element) : Element;

    /**
     * loads various content.
     *
     * @param mixed $content
     *
     * @return Element
     */
    public function load($content) : Element;
}
