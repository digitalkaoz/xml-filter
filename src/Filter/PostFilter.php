<?php

declare(strict_types=1);

namespace Rs\XmlFilter\Filter;

use Rs\XmlFilter\Document\Element;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostFilter extends AbstractFilter implements Filter
{
    /**
     * {@inheritdoc}
     */
    public function find(Element $element, array $options)
    {
        $value = $this->executeNestedFilter($element, $options);

        return $options['callable']($value);
    }

    private function executeNestedFilter(Element $element, array $options)
    {
        list($filter, $nestedOptions) = $this->splitFilterAndOptions($options['real_filter']);
        $nestedOptions['context'] = $options['context'];

        return $this->filter($element, $filter, $options, $nestedOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'callable', 'real_filter',
        ]);

        $resolver->setDefault('context', null);

        $resolver->setAllowedTypes('context', ['string', 'null', Element::class]);
        $resolver->setAllowedTypes('callable', 'callable');
        $resolver->setAllowedTypes('real_filter', 'array');
    }
}
