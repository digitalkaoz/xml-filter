<?php

declare(strict_types=1);

namespace Rs\XmlFilter\Filter;

use Rs\XmlFilter\Document\Element;
use Rs\XmlFilter\Exception\ReferenceException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReferenceFilter extends AbstractFilter implements Filter
{
    /**
     * {@inheritdoc}
     */
    public function find(Element $element, array $options)
    {
        if (!$options['value']) {
            $options['value'] = $options['reference'];
        }

        $this->checkReference($element, $options);

        list($filter, $nestedOptions) = $this->splitFilterAndOptions($options['value']);

        return $this->filter($element, $filter, $options, $nestedOptions);
    }

    private function checkReference(Element $element, array $options)
    {
        $ref = $this->manager->filter(
            $this->getSearchElement($element, $options),
            ScalarFilter::class,
            ['path' => $this->preparePath($options['reference'], $options)]
        );

        if (!$ref) {
            $errorElement = $element;
            if ($options['element']) {
                $errorElement = $element->find($options['element'])[0];
            }
            throw new ReferenceException(sprintf('reference (%s) could not be resolved', $options['reference']), $errorElement, $options);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['reference']);

        $resolver->setDefault('element', null);
        $resolver->setDefault('value', null);

        $resolver->setAllowedTypes('element', ['string', 'null']);
        $resolver->setAllowedTypes('reference', 'string');
        $resolver->setAllowedTypes('value', ['string', 'array', 'null']);

        parent::getOptions($resolver);
    }
}
