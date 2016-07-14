<?php

declare(strict_types=1);

namespace Rs\XmlFilter\Loader;

class ArrayLoader implements Loader
{
    /**
     * @var array
     */
    private $content = [];

    public function __construct(array $content)
    {
        $this->content = $content;
    }

    public function __invoke() : array
    {
        reset($this->content);

        return [
            'filter'  => key($this->content),
            'options' => current($this->content),
        ];
    }
}
