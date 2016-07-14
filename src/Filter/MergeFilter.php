<?php

declare(strict_types=1);

namespace Rs\XmlFilter\Filter;

use Rs\XmlFilter\Document\Element;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MergeFilter extends AbstractFilter implements Filter
{
    /**
     * {@inheritdoc}
     */
    public function find(Element $element, array $options)
    {
        $data = [];

        foreach ($options['filters'] as $filterSet) {
            $value = $this->executeNestedFilter($element, $options, $filterSet);
            $data = array_merge($data, $value);
        }

        return $this->sort($data, $options);
    }

    private function executeNestedFilter(Element $element, array $options, $filterSet) : array
    {
        if (is_string($filterSet)) {
            $filterSet = ['path' => $filterSet];
        }
        list($filter, $filterOptions) = $this->splitFilterAndOptions($filterSet);

        $value = $this->filter($element, $filter, $options, $filterOptions);

        return is_array($value) ? $value : [$value];
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['filters']);
        $resolver->setAllowedTypes('filters', 'array');

        $resolver->setDefault('sort', false);
        $resolver->setAllowedTypes('sort', 'bool');

        parent::getOptions($resolver);
    }
}
