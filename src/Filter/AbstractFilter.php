<?php

declare(strict_types=1);

namespace Rs\XmlFilter\Filter;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractFilter
{
    use Behavior\FilterDefaultsTrait;
    use Behavior\FilterConditionTrait;
    use Behavior\FilterContextTrait;
    use Behavior\FilterSortTrait;

    /**
     * @var Manager\FilterManager
     */
    protected $manager;

    public function __construct(Manager\FilterManager $manager)
    {
        $this->manager = $manager;
    }

    public function getOptions(OptionsResolver $resolver)
    {
        $this->getDefaultOptions($resolver);
    }

    protected function splitFilterAndOptions($options) : array
    {
        if (!is_array($options)) {
            $options = ['path' => $options];
        }

        $filter = $options['filter'] ?? ScalarFilter::class;

        unset($options['filter']);

        return [$filter, $options];
    }
}
