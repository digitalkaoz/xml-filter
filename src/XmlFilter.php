<?php

declare(strict_types=1);

namespace Rs\XmlFilter;

use Pimple\Container;
use Rs\XmlFilter\Container\XmlFilterProvider;
use Rs\XmlFilter\Document\Document;
use Rs\XmlFilter\Document\DomDocument;
use Rs\XmlFilter\Document\Element;
use Rs\XmlFilter\Document\SimpleXmlDocument;
use Rs\XmlFilter\Filter\Filter;
use Rs\XmlFilter\Filter\Manager\FilterManager;
use Rs\XmlFilter\Loader\Loader;

class XmlFilter
{
    /**
     * @var FilterManager
     */
    private $filterManager;

    public function __construct(FilterManager $filterManager)
    {
        $this->filterManager = $filterManager;
    }

    /**
     * @param Filter|callable $filter
     */
    public function addFilter(string $name, $filter)
    {
        $this->filterManager->add($name, $filter);
    }

    public static function load($content, string $class = null)
    {
        $document = self::createDocument($class);

        return $document->load($content);
    }

    private static function createDocument(string $class = null) : Document
    {
        if (null === $class) {
            $class = SimpleXmlDocument::class;
        } elseif (!class_exists($class)) {
            throw new \InvalidArgumentException('no suitable xml parser found');
        } elseif (!in_array(Document::class, class_implements($class), true)) {
            throw new \InvalidArgumentException('given class should implement Document');
        }

        if (extension_loaded('simplexml') && SimpleXmlDocument::class === $class) {
            return new SimpleXmlDocument();
        } elseif (extension_loaded('libxml') && DomDocument::class === $class) {
            return new DomDocument();
        }

        return new $class();
    }

    public function filter(Element $content, Loader $loader)
    {
        $config = $loader->__invoke();

        return $this->filterManager->filter(
            $content,
            $config['filter'],
            $config['options']
        );
    }

    public static function create(Container $container = null) : XmlFilter
    {
        $container = $container ?: new Container();
        $container->register(new XmlFilterProvider());

        return $container['xml_filter'];
    }
}
