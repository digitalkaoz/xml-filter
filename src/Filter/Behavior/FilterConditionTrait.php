<?php

declare(strict_types=1);

namespace Rs\XmlFilter\Filter\Behavior;

use Rs\XmlFilter\Document\Element;
use Rs\XmlFilter\Filter\Manager\FilterManager;

trait FilterConditionTrait
{
    protected function checkCondition($key, $value, $options, Element $element, FilterManager $filterManager = null) : bool
    {
        if (is_callable($options['condition']) ||
            (is_string($options['condition']) && function_exists($options['condition']))
        ) {
            return (bool) $options['condition']($key, $value, $element, $filterManager);
        }

        return !$options['condition'];
    }
}
