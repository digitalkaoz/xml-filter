<?php

namespace spec\Rs\XmlFilter\Document;

use PhpSpec\ObjectBehavior;
use Rs\XmlFilter\Document\Element;
use Rs\XmlFilter\Document\SimpleXmlElement;

/**
 * @mixin SimpleXmlElement
 * @covers SimpleXmlElement
 */
class SimpleXmlElementSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('<foo attr="val">bar</foo>');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(SimpleXmlElement::class);
        $this->shouldHaveType(Element::class);
        $this->shouldHaveType(\SimpleXMLElement::class);
    }

    public function it_is_stringable()
    {
        $this->__toString()->shouldBe('bar');
    }

    public function it_returns_an_array_on_find()
    {
        $this->find('/foo')->shouldHaveCount(1);
    }

    public function it_can_fetch_an_attribute_from_an_element()
    {
        $this->attribute('attr')->shouldBe('val');
        $this->attribute('unknown')->shouldBe('');
    }
}
