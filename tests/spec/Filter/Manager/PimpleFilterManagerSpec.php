<?php

namespace spec\Rs\XmlFilter\Filter\Manager;

use PhpSpec\ObjectBehavior;
use Pimple\Container;
use Rs\XmlFilter\Filter\Manager\FilterManager;
use Rs\XmlFilter\Filter\Manager\PimpleFilterManager;
use Rs\XmlFilter\Filter\ScalarFilter;
use spec\Rs\XmlFilter\Filter\FilterSpecTrait;
use Symfony\Component\OptionsResolver\Exception\ExceptionInterface;

/**
 * @mixin PimpleFilterManager
 * @covers PimpleFilterManager
 */
class PimpleFilterManagerSpec extends ObjectBehavior
{
    use FilterSpecTrait;

    public function it_is_initializable()
    {
        $this->shouldHaveType(PimpleFilterManager::class);
        $this->shouldHaveType(FilterManager::class);
        $this->shouldHaveType(Container::class);
    }

    public function it_raises_an_Exception_for_non_filters()
    {
        $closure = function () {
            return new \stdClass();
        };

        $this['foo'] = $closure;

        $this->shouldThrow(\RuntimeException::class)->during('offsetGet', ['foo']);
    }

    public function it_resolves_the_options_for_a_filter()
    {
        $element = $this->createElement('<doc>foo</doc>');
        $this[ScalarFilter::class] = new ScalarFilter($this->getWrappedObject());

        $this->shouldThrow(ExceptionInterface::class)->during('filter', [$element, ScalarFilter::class, []]);
    }

    public function it_executes_a_filter()
    {
        $element = $this->createElement('<doc>foo</doc>');
        $this[ScalarFilter::class] = new ScalarFilter($this->getWrappedObject());

        $this->filter($element, ScalarFilter::class, ['path' => '/doc'])->shouldBe('foo');
    }
}
