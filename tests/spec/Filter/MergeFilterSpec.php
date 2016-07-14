<?php

namespace spec\Rs\XmlFilter\Filter;

use PhpSpec\ObjectBehavior;
use Rs\XmlFilter\Filter\Filter;
use Rs\XmlFilter\Filter\MapFilter;
use Rs\XmlFilter\Filter\MergeFilter;
use Rs\XmlFilter\Filter\ScalarFilter;

/**
 * @mixin MergeFilter
 * @covers MergeFilter
 */
class MergeFilterSpec extends ObjectBehavior
{
    use FilterSpecTrait;

    public function let()
    {
        $this->injectManager();
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(MergeFilter::class);
        $this->shouldHaveType(Filter::class);
    }

    public function it_merges_multiple_scalar_filters()
    {
        $element = $this->createElement('<xml><bar>dd</bar><doc><foo>foo</foo><bar>bar</bar></doc><doc><foo>a</foo><bar>b</bar></doc></xml>');

        $this->find($element, $this->resolveOptions([
            'filters' => [
                '/xml/bar',
                ['path'   => '/xml/doc/foo', 'multiple' => true, 'sort' => true],
                ['filter' => ScalarFilter::class, 'path' => '/xml/doc/bar', 'multiple' => true, 'sort' => true],
            ],
        ]))->shouldBe([
            'dd',
            'a',
            'foo',
            'b',
            'bar',
        ]);
    }

    public function it_merges_multiple_filters_and_sorts_them()
    {
        $element = $this->createElement('<xml><doc><foo>foo</foo><bar>bar</bar></doc><doc><foo>a</foo><bar>b</bar></doc></xml>');

        $this->find($element, $this->resolveOptions([
            'filters' => [
                [
                    'filter'   => MapFilter::class,
                    'basePath' => '/xml/doc',
                    'key'      => './foo',
                    'value'    => './foo',
                ],
                [
                    'filter'   => MapFilter::class,
                    'basePath' => '/xml/doc',
                    'key'      => './bar',
                    'value'    => './bar',
                ],
            ],
            'sort'    => true,
        ]))->shouldBe([
            'a'   => 'a',
            'b'   => 'b',
            'bar' => 'bar',
            'foo' => 'foo',
        ]);
    }
}
