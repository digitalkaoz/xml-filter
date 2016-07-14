<?php

declare(strict_types=1);

namespace Rs\XmlFilter\Document;

use Rs\XmlFilter\Exception\EvaluationException;

class SimpleXmlElement extends \SimpleXMLElement implements Element
{
    public function find(string $path) : array
    {
        $result = $this->xpath($path);

        if (false === $result) {
            throw new EvaluationException(sprintf('unable to evaluate the "%s"', $path), $this, ['path' => $path]);
        }

        return $result;
    }

    public function attribute($name) : string
    {
        foreach ($this->attributes() as $key => $value) {
            if ($key === $name) {
                return (string) $value;
            }
        }

        return '';
    }
}
