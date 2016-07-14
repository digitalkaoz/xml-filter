<?php

declare(strict_types=1);

namespace Rs\XmlFilter\Container;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Rs\XmlFilter\Filter;
use Rs\XmlFilter\Filter\Manager\FilterManager;
use Rs\XmlFilter\Filter\Manager\PimpleFilterManager;

class FilterServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $pimple)
    {
        if (!$pimple instanceof FilterManager) {
            throw new \InvalidArgumentException('this provider can only be registered to a FilterManager');
        }

        $pimple[Filter\ScalarFilter::class] = function (PimpleFilterManager $pimple) {
            return new Filter\ScalarFilter($pimple['manager']);
        };

        $pimple[Filter\AggregateFilter::class] = function (PimpleFilterManager $pimple) {
            return new Filter\AggregateFilter($pimple['manager']);
        };

        $pimple[Filter\MapFilter::class] = function (PimpleFilterManager $pimple) {
            return new Filter\MapFilter($pimple['manager']);
        };

        $pimple[Filter\ReferenceFilter::class] = function (PimpleFilterManager $pimple) {
            return new Filter\ReferenceFilter($pimple['manager']);
        };

        $pimple[Filter\MergeFilter::class] = function (PimpleFilterManager $pimple) {
            return new Filter\MergeFilter($pimple['manager']);
        };

        $pimple[Filter\PostFilter::class] = function (PimpleFilterManager $pimple) {
            return new Filter\PostFilter($pimple['manager']);
        };
    }
}
