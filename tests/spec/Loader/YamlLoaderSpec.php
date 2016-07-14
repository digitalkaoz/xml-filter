<?php

namespace spec\Rs\XmlFilter\Loader;

use PhpSpec\ObjectBehavior;
use Rs\XmlFilter\Filter\ScalarFilter;
use Rs\XmlFilter\Loader\Loader;
use Rs\XmlFilter\Loader\YamlLoader;

/**
 * @mixin YamlLoader
 * @covers YamlLoader
 */
class YamlLoaderSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->beConstructedWith("Rs\\XmlFilter\\Filter\\ScalarFilter:\n\tpath: /foo");

        $this->shouldHaveType(YamlLoader::class);
        $this->shouldHaveType(Loader::class);
    }

    public function it_can_load_config_from_yaml_strings()
    {
        $this->beConstructedWith("Rs\\XmlFilter\\Filter\\ScalarFilter:\n  path: /foo");

        $this->__invoke()->shouldBe(['filter' => ScalarFilter::class, 'options' => ['path' => '/foo']]);
    }

    public function it_can_load_config_from_yaml_files()
    {
        $file = tempnam(sys_get_temp_dir(), 'xml-filter');

        file_put_contents($file, "Rs\\XmlFilter\\Filter\\ScalarFilter:\n  path: /foo");

        $this->beConstructedWith($file);

        $this->__invoke()->shouldBe(['filter' => ScalarFilter::class, 'options' => ['path' => '/foo']]);

        @unlink($file);
    }
}
