<?php

namespace spec\Rs\XmlFilter;

use PhpSpec\ObjectBehavior;
use Pimple\Container;
use Rs\XmlFilter\Document\Document;
use Rs\XmlFilter\Document\DomDocument;
use Rs\XmlFilter\Document\DomElement;
use Rs\XmlFilter\Document\Element;
use Rs\XmlFilter\Document\SimpleXmlDocument;
use Rs\XmlFilter\Document\SimpleXmlElement;
use Rs\XmlFilter\Filter\Filter;
use Rs\XmlFilter\Filter\ScalarFilter;
use Rs\XmlFilter\Loader\ArrayLoader;
use Rs\XmlFilter\XmlFilter;
use spec\Rs\XmlFilter\Filter\FilterSpecTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @mixin XmlFilter
 * @covers XmlFilter
 */
class XmlFilterSpec extends ObjectBehavior
{
    use FilterSpecTrait;

    public function let()
    {
        $this->injectManager();
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(XmlFilter::class);
    }

    public function it_can_create_elements(Document $doc, Element $element)
    {
        $doc->load('<foo></foo>')->willReturn($element);

        $this->load('<foo></foo>')->shouldHaveType(Element::class);
        $this->load('<foo></foo>', SimpleXmlDocument::class)->shouldHaveType(SimpleXmlElement::class);
        $this->load('<foo></foo>', DomDocument::class)->shouldHaveType(DomElement::class);

        $this->shouldThrow(\InvalidArgumentException::class)->during('load', ['<foo></foo>', '\stdClass']);
        $this->shouldThrow(\InvalidArgumentException::class)->during('load', ['<foo></foo>', 'UnknownClass']);
    }

    public function it_can_filter_a_xml()
    {
        $e = new SimpleXmlElement('<foo>foo</foo>');
        $loader = new ArrayLoader([
            ScalarFilter::class => ['path' => '/foo'],
        ]);

        $this->filter($e, $loader)->shouldBe('foo');
    }

    public function it_can_add_a_filter_instance_to_the_manager()
    {
        $this->addFilter(MyFilter::class, new MyFilter());

        $e = new SimpleXmlElement('<foo>foo</foo>');

        $loader = new ArrayLoader([
            MyFilter::class => [],
        ]);

        $this->filter($e, $loader)->shouldBe('foo');
    }

    public function it_can_add_a_filter_callable_to_the_manager()
    {
        $this->addFilter(MyFilter::class, function (Container $container) {
            return new MyFilter();
        });

        $e = new SimpleXmlElement('<foo>foo</foo>');

        $loader = new ArrayLoader([
            MyFilter::class => [],
        ]);

        $this->filter($e, $loader)->shouldBe('foo');
    }
}

class MyFilter implements Filter
{
    public function find(Element $element, array $options)
    {
        return 'foo';
    }

    public function getOptions(OptionsResolver $resolver)
    {
        return [];
    }
}
