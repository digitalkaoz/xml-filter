<?php

declare(strict_types=1);

namespace Rs\XmlFilter\Document;

interface Element
{
    public function find(string $path) : array;

    public function __toString();

    public function attribute($name) : string;
}
