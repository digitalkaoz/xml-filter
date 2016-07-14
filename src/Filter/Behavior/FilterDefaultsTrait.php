<?php

declare(strict_types=1);

namespace Rs\XmlFilter\Filter\Behavior;

use Rs\XmlFilter\Document\Element;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait FilterDefaultsTrait
{
    protected function getDefaultOptions(OptionsResolver $resolver)
    {
        $defaults = [
            'sort'      => true,
            'condition' => false,
            'validate'  => false,
            'context'   => null,
        ];

        foreach ($defaults as $name => $value) {
            if (!$resolver->hasDefault($name)) {
                $resolver->setDefault($name, $value);
            }
        }

        $resolver->setAllowedTypes('context', ['null', Element::class]);
        $resolver->setAllowedTypes('sort', ['bool', 'string']);
        $resolver->setAllowedTypes('condition', ['bool', 'callable']);
        $resolver->setAllowedTypes('validate', ['bool', 'callable']);
    }
}
