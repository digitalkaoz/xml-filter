<?php

declare(strict_types=1);

namespace Rs\XmlFilter\Loader;

interface Loader
{
    public function __invoke() : array;
}
