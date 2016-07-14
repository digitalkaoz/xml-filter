<?php

namespace spec\Rs\XmlFilter\Exception;

use PhpSpec\ObjectBehavior;
use Rs\XmlFilter\Document\Element;
use Rs\XmlFilter\Exception\Exception;

/**
 * @mixin Exception
 * @covers Exception
 */
class ExceptionSpec extends ObjectBehavior
{
    public function let(Element $e)
    {
        $this->beConstructedWith('foo', $e, ['path' => '/foo'], 'bar');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Exception::class);
        $this->shouldHaveType(\Exception::class);
    }

    public function it_provides_getters_for_data()
    {
        $this->getMessage()->shouldBe('foo');
        $this->getElement()->shouldHaveType(Element::class);
        $this->getOptions()->shouldBe(['path' => '/foo']);
        $this->getValue()->shouldBe('bar');
    }
}
