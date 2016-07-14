<?php

namespace spec\Rs\XmlFilter\Document;

use PhpSpec\ObjectBehavior;
use Rs\XmlFilter\Document\Document;
use Rs\XmlFilter\Document\SimpleXmlDocument;
use Rs\XmlFilter\Document\SimpleXmlElement;

/**
 * @mixin SimpleXmlDocument
 * @covers SimpleXmlDocument
 */
class SimpleXmlDocumentSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(SimpleXmlDocument::class);
        $this->shouldHaveType(Document::class);
    }

    public function it_can_load_strings()
    {
        $this->loadString('<foo></foo>')->shouldHaveType(SimpleXmlElement::class);
        $this->load('<foo></foo>')->shouldHaveType(SimpleXmlElement::class);
    }

    public function it_can_load_streams()
    {
        $stream = fopen('php://memory', 'rw');
        fwrite($stream, '<foo></foo>');

        $this->loadStream($stream)->shouldHaveType(SimpleXmlElement::class);
        $this->load($stream)->shouldHaveType(SimpleXmlElement::class);
    }

    public function it_can_load_files()
    {
        $file = tempnam(sys_get_temp_dir(), 'rs_xml_filter');
        file_put_contents($file, '<foo></foo>');

        $this->loadFile($file)->shouldHaveType(SimpleXmlElement::class);
        $this->load($file)->shouldHaveType(SimpleXmlElement::class);

        @unlink($file);
    }

    public function it_can_load_DOMNodes()
    {
        $doc = new \DOMDocument();
        $doc->loadXML('<foo>foo</foo>');

        $this->loadDOM($doc)->shouldHaveType(SimpleXmlElement::class);
        $this->load($doc)->shouldHaveType(SimpleXmlElement::class);
    }

    public function it_can_load_SimpleXmlElements()
    {
        $this->loadSimpleXml(new \SimpleXMLElement('<foo>foo</foo>'))->shouldHaveType(SimpleXmlElement::class);
        $this->load(new \SimpleXMLElement('<foo>foo</foo>'))->shouldHaveType(SimpleXmlElement::class);
    }
}
