<?php

namespace spec\Rs\XmlFilter\Container;

use PhpSpec\ObjectBehavior;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Prophecy\Argument;
use Rs\XmlFilter\Container\XmlFilterProvider;

/**
 * @mixin XmlFilterProvider
 * @covers XmlFilterProvider
 */
class XmlFilterProviderSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(XmlFilterProvider::class);
        $this->shouldHaveType(ServiceProviderInterface::class);
    }

    public function it_registers_the_needed_services(Container $container)
    {
        $this->register($container);

        $container->offsetSet('filter_manager', Argument::type('callable'))->shouldHaveBeenCalled();
    }
}
