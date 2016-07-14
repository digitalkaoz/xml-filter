<?php

declare(strict_types=1);

namespace Rs\XmlFilter\Exception;

use Rs\XmlFilter\Document\Element;

interface FilterException
{
    public function getOptions() : array;

    public function getElement() : Element;

    public function getValue();
}
