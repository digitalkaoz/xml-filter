<?php

declare(strict_types=1);

namespace Rs\XmlFilter\Filter\Manager;

use Rs\XmlFilter\Document\Element;
use Rs\XmlFilter\Filter\Filter;

class ValidationFilterManager implements FilterManager
{
    /**
     * @var FilterManager
     */
    protected $filterManager;

    public function __construct(FilterManager $filterManager)
    {
        $this->filterManager = $filterManager;
    }

    public function filter(Element $element, string $filter, array $options)
    {
        $result = $this->filterManager->filter($element, $filter, $options);

        if (isset($options['validate']) && is_callable($options['validate'])) {
            $options['validate']($result);
        }

        return $result;
    }

    /**
     * @param Filter|callable $filter
     */
    public function add(string $name, $filter)
    {
        $this->filterManager->add($name, $filter);
    }
}
