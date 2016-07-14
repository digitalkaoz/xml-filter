<?php

declare(strict_types=1);

namespace Rs\XmlFilter\Filter;

use Rs\XmlFilter\Document\Element;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface Filter
{
    /**
     * filters a data structure out of the element.
     *
     * @param Element $element
     * @param array   $options
     *
     * @return string|array
     */
    public function find(Element $element, array $options);

    /**
     * defines the options applicable for this filter.
     *
     * @param OptionsResolver $resolver
     */
    public function getOptions(OptionsResolver $resolver);
}
