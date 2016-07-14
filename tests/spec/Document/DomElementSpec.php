<?php

namespace spec\Rs\XmlFilter\Document;

use PhpSpec\ObjectBehavior;
use Rs\XmlFilter\Document\DomDocument;
use Rs\XmlFilter\Document\DomElement;
use Rs\XmlFilter\Document\Element;

/**
 * @mixin DomElement
 * @covers DomElement
 */
class DomElementSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedThrough([$this, 'createElement']);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(DomElement::class);
        $this->shouldImplement(Element::class);
        $this->shouldHaveType(\DOMElement::class);
        $this->shouldHaveType(\DOMNode::class);
    }

    public function it_is_stringable()
    {
        $this->__toString()->shouldBe('bar');
    }

    public function it_returns_arrays_on_find()
    {
        $this->find('/foo')->shouldHaveCount(1);
    }

    public function it_can_fetch_an_attribute_from_an_element()
    {
        $this->attribute('attr')->shouldBe('val');
        $this->attribute('unknown')->shouldBe('');
    }

    public function createElement()
    {
        $doc = new DomDocument();

        return $doc->load('<foo attr="val">bar</foo>');
    }
}
