<?php

declare(strict_types=1);

namespace Rs\XmlFilter\Filter\Manager;

use Pimple\Container;
use Rs\XmlFilter\Document\Element;
use Rs\XmlFilter\Filter\Filter;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PimpleFilterManager extends Container implements FilterManager
{
    public function filter(Element $element, string $filter, array $options)
    {
        $filter = $this[$this->normalizeId($filter)];
        /* @var Filter $filter */

        $options = $this->resolveOptions($filter, $options);

        return $filter->find($element, $options);
    }

    private function resolveOptions(Filter $filter, array $options) : array
    {
        $resolver = new OptionsResolver();
        $filter->getOptions($resolver);

        return $resolver->resolve($options);
    }

    public function offsetGet($id)
    {
        $filter = parent::offsetGet($id);

        if ('manager' !== $id && !$filter instanceof Filter) {
            throw new \RuntimeException('invalid service, should implement Filter');
        }

        return $filter;
    }

    private function normalizeId($id)
    {
        return str_replace('\\\\', '\\', $id);
    }

    /**
     * @param Filter|callable $filter
     */
    public function add(string $name, $filter)
    {
        $this[$name] = $filter;
    }
}
