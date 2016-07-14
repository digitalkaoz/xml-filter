<?php

declare(strict_types=1);

namespace Rs\XmlFilter\Filter\Manager;

use Rs\XmlFilter\Document\Element;
use Rs\XmlFilter\Filter\Filter;

interface FilterManager
{
    public function filter(Element $element, string $filter, array $options);

    /**
     * @param Filter|callable $filter
     */
    public function add(string $name, $filter);
}
