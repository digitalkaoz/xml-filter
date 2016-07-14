<?php

namespace spec\Rs\XmlFilter\Container;

use PhpSpec\ObjectBehavior;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Prophecy\Argument;
use Rs\XmlFilter\Container\FilterServiceProvider;
use Rs\XmlFilter\Filter\AggregateFilter;
use Rs\XmlFilter\Filter\Manager\PimpleFilterManager;
use Rs\XmlFilter\Filter\MapFilter;
use Rs\XmlFilter\Filter\MergeFilter;
use Rs\XmlFilter\Filter\ReferenceFilter;
use Rs\XmlFilter\Filter\ScalarFilter;

/**
 * @mixin FilterServiceProvider
 * @covers FilterServiceProvider
 */
class FilterServiceProviderSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(FilterServiceProvider::class);
        $this->shouldHaveType(ServiceProviderInterface::class);
    }

    public function it_registers_the_filters(PimpleFilterManager $container)
    {
        $this->register($container);

        $ids = [
            ScalarFilter::class,
            AggregateFilter::class,
            MapFilter::class,
            ReferenceFilter::class,
            MergeFilter::class,
        ];

        foreach ($ids as $id) {
            $container->offsetSet($id, Argument::type('callable'))->shouldHaveBeenCalled();
        }
    }

    public function it_only_accepts_filter_manager_containers(Container $container)
    {
        $this->shouldThrow(\InvalidArgumentException::class)->during('register', [$container]);
    }
}
