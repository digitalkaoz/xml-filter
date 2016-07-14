<?php

namespace spec\Rs\XmlFilter\Loader;

use PhpSpec\ObjectBehavior;
use Rs\XmlFilter\Filter\ScalarFilter;
use Rs\XmlFilter\Loader\ArrayLoader;
use Rs\XmlFilter\Loader\Loader;

/**
 * @mixin ArrayLoader
 * @covers ArrayLoader
 */
class ArrayLoaderSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith([ScalarFilter::class => ['path' => '/foo']]);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ArrayLoader::class);
        $this->shouldHaveType(Loader::class);
    }

    public function it_returns_the_configuration_for_the_given_array()
    {
        $this->__invoke()->shouldBe(['filter' => ScalarFilter::class, 'options' => ['path' => '/foo']]);
    }
}
