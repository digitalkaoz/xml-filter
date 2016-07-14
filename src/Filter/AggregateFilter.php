<?php

declare(strict_types=1);

namespace Rs\XmlFilter\Filter;

use Rs\XmlFilter\Document\Element;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AggregateFilter extends AbstractFilter implements Filter
{
    /**
     * {@inheritdoc}
     */
    public function find(Element $element, array $options)
    {
        $data = [];

        foreach ($options['mappings'] as $key => $settings) {
            $value = $this->findValue($element, $settings, $options);

            if (false === $this->checkCondition($key, $value, $options, $element, $this->manager)) {
                continue;
            }

            $data[$key] = $value;
        }

        return $this->sort($data, $options);
    }

    private function findValue(Element $element, $settings, array $options)
    {
        list($filter, $nestedOptions) = $this->prepareNestedFilter($settings, $options);

        return $this->filter($element, $filter, $options, $nestedOptions);
    }

    private function prepareNestedFilter($settings, array $options) : array
    {
        $filter = ScalarFilter::class;
        $nestedOptions = [];

        if (is_string($settings) || !isset($settings['filter'])) {
            $nestedOptions = ['path' => $this->preparePath(is_string($settings) ? $settings : $settings['path'], $options)];
        }

        if (is_array($settings)) {
            list($filter, $settings) = $this->splitFilterAndOptions($settings);

            $nestedOptions = array_merge($settings, $nestedOptions ?? []);
        }

        return [$filter, $nestedOptions];
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(OptionsResolver $resolver)
    {
        parent::getOptions($resolver);

        $resolver->remove(['cast', 'multiple']);

        $resolver->setRequired('mappings');
        $resolver->setAllowedTypes('mappings', ['array']);
    }
}
