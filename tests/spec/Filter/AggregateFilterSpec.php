<?php

namespace spec\Rs\XmlFilter\Filter;

use PhpSpec\ObjectBehavior;
use Rs\XmlFilter\Filter\AggregateFilter;
use Rs\XmlFilter\Filter\Filter;

/**
 * @mixin AggregateFilter
 * @covers AggregateFilter
 */
class AggregateFilterSpec extends ObjectBehavior
{
    use FilterSpecTrait;

    public function let()
    {
        $this->injectManager();
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(AggregateFilter::class);
        $this->shouldHaveType(Filter::class);
    }

    public function it_can_aggregate_simple_filters()
    {
        $element = $this->createElement('<doc><foo>foo</foo><bar>10</bar></doc>');

        $this->find($element, $this->resolveOptions(['mappings' => [
            'foo' => '/doc/foo',
            'bar' => ['cast' => 'int', 'path' => '/doc/bar'],
        ]]))->shouldBe([
            'bar' => 10,
            'foo' => 'foo',
        ]);
    }

    public function it_can_execute_other_filters()
    {
        $element = $this->createElement('<doc><foo>foo</foo><bar>10</bar></doc>');

        $this->find($element, $this->resolveOptions(['mappings' => [
            'bazz' => [
                'filter'   => AggregateFilter::class,
                'mappings' => [
                    'foo' => '/doc/foo',
                    'bar' => '/doc/bar',
                ],
            ],
        ]]))->shouldBe([
            'bazz' => [
                'bar' => '10',
                'foo' => 'foo',
            ],
        ]);
    }

    public function it_can_sort_the_values()
    {
        $element = $this->createElement('<doc><foo>foo</foo><bar>10</bar></doc>');

        $this->find($element, $this->resolveOptions(['sort' => false, 'mappings' => [
                'foo' => '/doc/foo',
                'bar' => '/doc/bar',
        ]]))->shouldBe([
            'foo' => 'foo',
            'bar' => '10',
        ]);
    }

    public function it_can_filter_keys_with_a_callable()
    {
        $element = $this->createElement('<doc><foo>foo</foo><bar>10</bar></doc>');

        $this->find($element, $this->resolveOptions(['mappings' => [
            'foo' => '/doc/foo',
            'bar' => '/doc/bar',
        ],
        'condition' => function ($key, $value) {
            return 'foo' !== $key;
        }, ]))->shouldBe([
            'bar' => '10',
        ]);
    }

    public function it_can_filter_values_with_a_callable()
    {
        $element = $this->createElement('<doc><foo>foo</foo><bar>10</bar></doc>');

        $this->find($element, $this->resolveOptions(['mappings' => [
            'foo' => '/doc/foo',
            'bar' => '/doc/bar',
        ],
        'condition' => function ($key, $value) {
            return '10' !== $value;
        }, ]))->shouldBe([
            'foo' => 'foo',
        ]);
    }

    public function it_can_use_a_context_path_for_all_embedded_filters()
    {
        $element = $this->createElement('<doc><foo>foo</foo><bar>10</bar></doc>');
        $context = $element->find('/doc')[0];

        $this->find($element, $this->resolveOptions(['context' => $context, 'mappings' => [
            'foo' => './foo',
            'bar' => ['cast' => 'int', 'path' => './bar'],
        ]]))->shouldBe([
            'bar' => 10,
            'foo' => 'foo',
        ]);
    }
}
