<?php

declare(strict_types=1);

namespace Rs\XmlFilter\Filter;

use Rs\XmlFilter\Document\Element;
use Rs\XmlFilter\Exception\AmbigousValueException;
use Rs\XmlFilter\Exception\NoValueException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScalarFilter extends AbstractFilter implements Filter
{
    /**
     * {@inheritdoc}
     */
    public function find(Element $element, array $options)
    {
        $nodes = $element->find($this->preparePath($options['path'], $options));

        if (true === $options['multiple']) {
            return $this->findMultiple($element, $nodes, $options);
        }

        if (1 === count($nodes)) {
            return $this->findSingle($nodes, $options);
        }

        if (0 === count($nodes)) {
            if ($options['nullable']) {
                return;
            }

            throw new NoValueException(sprintf('no value detected! "%s"', $options['path']), $element, $options);
        }

        $error = sprintf('non unique filter result detected! "%s" has %d different values (%s).', $options['path'], count($nodes), implode(',', $nodes));

        throw new AmbigousValueException($error, $element, $options, $nodes);
    }

    private function cast(Element $node, string $cast)
    {
        $value = (string) $node;

        settype($value, $cast);

        return $value;
    }

    private function findMultiple(Element $element, array $nodes, array $options) : array
    {
        $values = [];
        foreach ($nodes as $index => $node) {
            /* @var Element $node */

            if (is_string($options['sort'])) {
                $index = $this->find($this->getSearchElement($element, $options), array_merge($options, [
                    'multiple' => false,
                    'context'  => $node,
                    'path'     => $this->preparePath($options['sort'], $options),
                ]));
            }

            $value = $this->cast($node, $options['cast']);

            if (false === $this->checkCondition($index, $value, $options, $node)) {
                continue;
            }

            $values[$index] = $value;
        }

        $values = $this->sort($values, $options);

        return array_values($values);
    }

    private function findSingle(array $nodes, array $options)
    {
        $node = reset($nodes);

        $value = $this->cast($node, $options['cast']);

        if ($this->checkCondition(null, $value, $options, $node)) {
            return $value;
        }
    }

    protected function sort(array $data, array $options): array
    {
        if (true === $options['sort']) {
            sort($data);
        } elseif (is_string($options['sort'])) {
            ksort($data);
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'path',

        ]);

        $resolver->setDefaults([
            'nullable'         => true,
            'multiple'         => false,
            'cast'             => 'string',
        ]);
        $resolver->remove('context');
        $resolver->setAllowedTypes('path', 'string');
        $resolver->setAllowedTypes('nullable', 'bool');
        $resolver->setAllowedTypes('multiple', 'boolean');
        $resolver->setAllowedValues('cast', ['string', 'int', 'float', 'bool']);

        parent::getOptions($resolver);
    }
}
