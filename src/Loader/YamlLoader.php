<?php

declare(strict_types=1);

namespace Rs\XmlFilter\Loader;

use Symfony\Component\Yaml\Yaml;

class YamlLoader extends ArrayLoader implements Loader
{
    public function __construct(string $content)
    {
        if (is_file($content) && is_readable($content)) {
            $content = file_get_contents($content);
        }

        parent::__construct(Yaml::parse($content));
    }
}
