<?php

declare(strict_types=1);

namespace Rs\XmlFilter\Loader;

use Symfony\Component\Yaml\Yaml;
use Webmozart\Assert\Assert;

class YamlLoader extends ArrayLoader implements Loader
{
    public function __construct(string $content)
    {
        if (is_file($content) && is_readable($content)) {
            $content = file_get_contents($content);
        }

        $content = Yaml::parse($content);

        Assert::isArray($content, 'config could not be parsed');

        parent::__construct($content);
    }
}
