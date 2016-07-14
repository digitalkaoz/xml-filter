<?php

namespace spec\Rs\XmlFilter\Filter;

use PhpSpec\ObjectBehavior;
use Rs\XmlFilter\Filter\AggregateFilter;
use Rs\XmlFilter\Filter\Filter;
use Rs\XmlFilter\Filter\MapFilter;

/**
 * @mixin MapFilter
 * @covers MapFilter
 */
class MapFilterSpec extends ObjectBehavior
{
    use FilterSpecTrait;

    public function let()
    {
        $this->injectManager();
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(MapFilter::class);
        $this->shouldHaveType(Filter::class);
    }

    public function it_can_filter_a_map_out_of_xml()
    {
        $element = $this->createElement('<doc><foo>foo</foo><bar>bar</bar></doc>');

        $this->find($element, $this->resolveOptions([
            'basePath' => '/doc',
            'key'      => './foo',
            'value'    => './bar',
        ]))->shouldBe([
            'foo' => 'bar',
        ]);
    }

    public function it_can_handle_multiple_values()
    {
        $element = $this->createElement('<doc><foo>foo</foo><bar>bar</bar><bar>bazz</bar></doc>');

        $this->find($element, $this->resolveOptions([
            'basePath' => '/doc',
            'key'      => './foo',
            'value'    => ['path' => './bar', 'multiple' => true],
        ]))->shouldBe([
            'foo' => ['bar', 'bazz'],
        ]);
    }

    public function it_can_sort_the_map()
    {
        $element = $this->createElement('<xml><doc><foo>foo</foo><bar>bar</bar></doc><doc><foo>a</foo><bar>b</bar></doc></xml>');

        $this->find($element, $this->resolveOptions([
            'basePath' => '/xml/doc',
            'key'      => './foo',
            'value'    => './bar',
            'sort'     => false,
        ]))->shouldBe([
            'foo' => 'bar',
            'a'   => 'b',
        ]);
    }

    public function it_can_cast_the_values()
    {
        $element = $this->createElement('<xml><doc><foo>foo</foo><bar>10</bar></doc><doc><foo>a</foo><bar>1.2</bar></doc></xml>');

        $this->find($element, $this->resolveOptions([
            'basePath' => '/xml/doc',
            'key'      => './foo',
            'value'    => ['path' => './bar', 'cast' => 'float'],
        ]))->shouldBe([
            'a'   => 1.20,
            'foo' => 10.00,
        ]);
    }

    public function it_can_filter_keys_with_a_callable()
    {
        $element = $this->createElement('<xml><doc><foo>foo</foo><bar>10</bar></doc><doc><foo>a</foo><bar>1.2</bar></doc></xml>');

        $this->find($element, $this->resolveOptions([
            'basePath'  => '/xml/doc',
            'key'       => './foo',
            'value'     => ['path' => './bar', 'sort' => true, 'cast' => 'float'],
            'condition' => function ($key, $value) {
                return 'a' !== $key;
            }, ]))->shouldBe([
            'foo' => 10.00,
        ]);
    }

    public function it_can_filter_values_with_a_callable()
    {
        $element = $this->createElement('<xml><doc><foo>foo</foo><bar>10</bar></doc><doc><foo>a</foo><bar>1.2</bar></doc></xml>');

        $this->find($element, $this->resolveOptions([
            'basePath'  => '/xml/doc',
            'key'       => './foo',
            'value'     => ['path' => './bar', 'cast' => 'float'],
            'condition' => function ($key, $value) {
                return 10.00 !== $value;
            }, ]))->shouldBe([
            'a' => 1.20,
        ]);
    }

    public function it_can_call_other_filters_for_the_values()
    {
        $element = $this->createElement('<xml><doc><foo>foo</foo><bar>10</bar></doc></xml>');

        $this->find($element, $this->resolveOptions([
            'basePath' => '/xml/doc',
            'key'      => './foo',
            'value'    => [
                'filter'   => AggregateFilter::class,
                'mappings' => [
                    'foo' => '/xml/doc/foo',
                    'bar' => '/xml/doc/bar',
                ],
            ],
        ]))->shouldBe([
            'foo' => [
                'bar' => '10',
                'foo' => 'foo',
            ],
        ]);
    }

    public function it_can_pass_an_evaluated_context_into_nested_filters()
    {
        $element = $this->createElement('<xml><doc><foo>foo</foo><bar>10</bar></doc></xml>');

        $this->find($element, $this->resolveOptions([
            'basePath' => '/xml/doc',
            'value'    => [
                'filter'   => AggregateFilter::class,
                'mappings' => [
                    'foo' => './foo',
                    'bar' => './bar',
                ],
            ],
        ]))->shouldBe([
            [
                'bar' => '10',
                'foo' => 'foo',
            ],
        ]);
    }

    public function it_can_find_values_without_keys()
    {
        $element = $this->createElement('<xml><doc><foo>foo</foo><bar>bar</bar></doc><doc><foo>a</foo><bar>b</bar></doc></xml>');

        $this->find($element, $this->resolveOptions([
            'basePath' => '/xml/doc',
            'value'    => './bar',
        ]))->shouldBe([
            'bar',
            'b',
        ]);
    }

    public function it_can_sort_by_keys_and_return_only_their_values()
    {
        $element = $this->createElement('<xml><doc><foo>foo</foo><bar>bar</bar></doc><doc><foo>a</foo><bar>b</bar></doc></xml>');

        $this->find($element, $this->resolveOptions([
            'basePath'   => '/xml/doc',
            'key'        => './foo',
            'value'      => './bar',
            'valuesOnly' => true,
        ]))->shouldBe([
            'b',
            'bar',
        ]);
    }

    public function it_can_sort_values_by_an_xpath()
    {
        $element = $this->createElement('
<xml>
    <doc><foo>b</foo><bar>b</bar></doc>
    <doc><foo>a</foo><bar>bar</bar></doc>
</xml>
');

        $this->find($element, $this->resolveOptions([
            'basePath'   => '/xml/doc',
            'key'        => './foo',
            'value'      => './bar',
            'sort'       => './foo',
        ]))->shouldBe([
            'a' => 'bar',
            'b' => 'b',
        ]);
    }
}
