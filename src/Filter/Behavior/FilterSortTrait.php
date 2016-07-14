<?php

declare(strict_types=1);

namespace Rs\XmlFilter\Filter\Behavior;

trait FilterSortTrait
{
    protected function sort(array $data, array $options): array
    {
        if (true === $options['sort']) {
            ksort($data);
        }

        return $data;
    }
}
