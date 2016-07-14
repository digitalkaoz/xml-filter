<?php

namespace spec\Rs\XmlFilter\Document;

use PhpSpec\ObjectBehavior;
use Rs\XmlFilter\Document\Document;
use Rs\XmlFilter\Document\DomDocument;
use Rs\XmlFilter\Document\DomElement;

/**
 * @mixin DOMDocument
 * @covers DOMDocument
 */
class DomDocumentSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(DomDocument::class);
        $this->shouldHaveType(Document::class);
    }

    public function it_can_load_strings()
    {
        $this->loadString('<foo></foo>')->shouldHaveType(DomElement::class);
        $this->load('<foo></foo>')->shouldHaveType(DomElement::class);
    }

    public function it_can_load_streams()
    {
        $stream = fopen('php://memory', 'rw');
        fwrite($stream, '<foo></foo>');

        $this->loadStream($stream)->shouldHaveType(DomElement::class);
        $this->load($stream)->shouldHaveType(DomElement::class);
    }

    public function it_can_load_files()
    {
        $file = tempnam(sys_get_temp_dir(), 'rs_xml_filter');
        file_put_contents($file, '<foo></foo>');

        $this->loadFile($file)->shouldHaveType(DomElement::class);
        $this->load($file)->shouldHaveType(DomElement::class);

        @unlink($file);
    }

    public function it_can_load_DOMDocuments()
    {
        $doc = new \DOMDocument();
        $doc->loadXML('<foo>foo</foo>');

        $this->loadDOM($doc)->shouldHaveType(DomElement::class);
        $this->load($doc)->shouldHaveType(DomElement::class);
    }

    public function it_can_load_DOMNodes()
    {
        $node = new \DOMElement('fo', 'foo');
        $node->textContent = '<foo>foo</foo>';

        $this->loadDOM($node)->shouldHaveType(DomElement::class);
        $this->load($node)->shouldHaveType(DomElement::class);
    }

    public function it_can_load_SimpleXmlElements()
    {
        $this->loadSimpleXml(new \SimpleXMLElement('<foo>foo</foo>'))->shouldHaveType(DomElement::class);
        $this->load(new \SimpleXMLElement('<foo>foo</foo>'))->shouldHaveType(DomElement::class);
    }
}
