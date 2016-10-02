<?php

declare(strict_types=1);

namespace Rs\XmlFilter\Filter\Behavior;

use Rs\XmlFilter\Document\Element;
use Rs\XmlFilter\Document\SimpleXmlElement;
use Rs\XmlFilter\Filter\Manager\FilterManager;

trait FilterContextTrait
{
    /**
     * @var FilterManager
     */
    protected $manager;

    protected function filter(Element $element, string $filter, array $currentOptions, array $filterOptions)
    {
        return $this->manager->filter($this->getSearchElement($element, $currentOptions), $filter, $filterOptions);
    }

    protected function preparePath(string $path, $options) : string
    {
        if ($options['context'] instanceof Element && false !== strpos($path, '=./')) {
            //replace the search condition with the value from the context element
            preg_match('/=(\.\/[^]]+)]/', $path, $matches);
            $contextPath = $matches[1] ?? null;

            if ($contextPath !== null && $contextValue = $options['context']->find($contextPath)) {
                $contextValue = $contextValue[0] instanceof SimpleXmlElement ? (string) $contextValue[0] : $contextValue[0]->wholeText;

                return preg_replace('/=(\.\/[^]]+)]/', '="' . $contextValue . '"]', $path);
            }
        }

        if ($options['context'] instanceof Element) {
            return $path;
        }

        return sprintf($path, $options['context']);
    }

    protected function getSearchElement(Element $element, $options)
    {
        if (!isset($options['context']) || !$options['context'] instanceof Element) {
            return $element;
        }

        if (isset($options['path']) && 0 !== strpos($options['path'], '.')) {
            return $element;
        }

        return $options['context'];
    }
}
