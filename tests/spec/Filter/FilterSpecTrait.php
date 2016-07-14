<?php

declare(strict_types=1);

namespace spec\Rs\XmlFilter\Filter;

use Rs\XmlFilter\Container\FilterServiceProvider;
use Rs\XmlFilter\Document\DomDocument;
use Rs\XmlFilter\Document\Element;
use Rs\XmlFilter\Document\SimpleXmlDocument;
use Rs\XmlFilter\Filter\Manager\PimpleFilterManager;
use Rs\XmlFilter\Filter\Manager\ValidationFilterManager;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait FilterSpecTrait
{
    public function resolveOptions(array $options) : array
    {
        $resolver = new OptionsResolver();
        $this->getWrappedObject()->getOptions($resolver);

        return $resolver->resolve($options);
    }

    public function injectManager()
    {
        $container = new PimpleFilterManager();
        $container->register(new FilterServiceProvider());

        $manager = new ValidationFilterManager($container);

        $container['manager'] = $manager;

        $this->beConstructedWith($manager);
    }

    public function createElement($content) : Element
    {
        $class = getenv('ELEMENT_CLASS');

        if ('SimpleXML' === $class || !$class) {
            $doc = new SimpleXmlDocument();
        } else {
            $doc = new DomDocument();
        }

        return $doc->loadString($content);
    }
}
