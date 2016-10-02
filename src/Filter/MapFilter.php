<?php

declare(strict_types=1);

namespace Rs\XmlFilter\Filter;

use Rs\XmlFilter\Document\Element;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MapFilter extends AbstractFilter implements Filter
{
    /**
     * {@inheritdoc}
     */
    public function find(Element $element, array $options)
    {
        $results = [];
        $sort = [];

        foreach ($this->getNodes($element, $options) as $index => $node) {
            $key = $this->getKey($node, $options, $index);
            $value = $this->getValue($node, $options, $element);

            if (false === $this->checkCondition($key, $value, $options, $node, $this->manager)) {
                continue;
            }

            if (is_string($options['sort'])) {
                $sort[$key] = $this->manager->filter($node, ScalarFilter::class, ['path' => $options['sort'], 'nullable' => false]);
            }

            $results[$key] = $value;
        }

        return $this->prepareResult($options, $sort, $results);
    }

    private function prepareResult(array $options, $sort, array $results) : array
    {
        if ($sort) {
            asort($sort);
            foreach ($sort as $key => $result) {
                $sort[$key] = $results[$key];
            }
            $results = $sort;

            return $results;
        } else {
            $results = $this->sort($results, $options);
        }

        if ($options['valuesOnly'] ?? false) {
            return array_values($results);
        }

        return $results;
    }

    private function getNodes(Element $element, array $options) : array
    {
        return $element->find($this->preparePath($options['basePath'], $options));
    }

    private function getKey(Element $node, array $options, $default)
    {
        if ($options['key']) {
            $path = $this->preparePath($options['key'], $options);

            return $this->manager->filter($node, ScalarFilter::class, ['path' => $path, 'nullable' => false]);
        }

        return $default;
    }

    private function getValue(Element $node, array $options, Element $element)
    {
        if (is_string($options['value'])) {
            $path = $this->preparePath($options['value'], $options);

            return $this->manager->filter($node, ScalarFilter::class, ['path' => $path]);
        }

        return $this->findNested($node, $options, $element);
    }

    private function findNested(Element $node, array $options, Element $element)
    {
        list($filter, $nestedOptions) = $this->splitFilterAndOptions($options['value']);
        $nestedOptions['context'] = $node;
        $element = $filter === ScalarFilter::class ? $node : $element;

        return $this->manager->filter($element, $filter, $nestedOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(OptionsResolver $resolver)
    {
        parent::getOptions($resolver);
        $resolver->setRequired(['basePath', 'value']);

        $resolver->setDefaults([
            'key'        => null,
            'valuesOnly' => false,
        ]);

        $resolver->setAllowedTypes('basePath', 'string');
        $resolver->setAllowedTypes('key', ['string', 'null']);
        $resolver->setAllowedTypes('valuesOnly', ['boolean', 'null']);
        $resolver->setAllowedTypes('value', ['string', 'array']);
    }
}
