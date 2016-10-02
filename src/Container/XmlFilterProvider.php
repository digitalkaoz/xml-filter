<?php

declare(strict_types=1);

namespace Rs\XmlFilter\Container;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Rs\XmlFilter\Filter\Manager\PimpleFilterManager;
use Rs\XmlFilter\Filter\Manager\ValidationFilterManager;
use Rs\XmlFilter\XmlFilter;

class XmlFilterProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $pimple)
    {
        $pimple['pimple_manager'] = function () {
            $manager = new PimpleFilterManager();
            $manager->register(new FilterServiceProvider());

            $manager['manager'] = $manager;

            return $manager;
        };

        $pimple['validation_manager'] = function ($pimple) {
            $manager = new ValidationFilterManager($pimple['pimple_manager']);

            $pimple['pimple_manager']['manager'] = $manager;

            return $manager;
        };

        $pimple['filter_manager'] = function ($pimple) {
            return $pimple['validation_manager'];
        };

        $pimple['xml_filter'] = function ($pimple) {
            return new XmlFilter($pimple['filter_manager']);
        };
    }
}
