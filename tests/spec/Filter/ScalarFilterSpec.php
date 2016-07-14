<?php

namespace spec\Rs\XmlFilter\Filter;

use PhpSpec\ObjectBehavior;
use Rs\XmlFilter\Exception\AmbigousValueException;
use Rs\XmlFilter\Exception\NoValueException;
use Rs\XmlFilter\Filter\Filter;
use Rs\XmlFilter\Filter\ScalarFilter;

/**
 * @mixin ScalarFilter
 * @covers ScalarFilter
 */
class ScalarFilterSpec extends ObjectBehavior
{
    use FilterSpecTrait;

    public function let()
    {
        $this->injectManager();
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ScalarFilter::class);
        $this->shouldHaveType(Filter::class);
    }

    public function it_can_extract_single_values_with_type_casting()
    {
        $element = $this->createElement('<doc><foo>10.01</foo></doc>');

        $this->find($element, $this->resolveOptions(['path' => '/doc/foo']))->shouldBe('10.01');
        $this->find($element, $this->resolveOptions(['path' => '/doc/foo', 'cast' => 'int']))->shouldBe(10);
        $this->find($element, $this->resolveOptions(['path' => '/doc/foo', 'cast' => 'float']))->shouldBe(10.01);
        $this->find($element, $this->resolveOptions(['path' => '/doc/foo', 'cast' => 'bool']))->shouldBe(true);
    }

    public function it_can_extract_multiple_values()
    {
        $element = $this->createElement('<doc><foo>bar</foo><foo>1.3</foo></doc>');

        $this->find($element, $this->resolveOptions(['path' => '/doc/foo', 'multiple' => true]))->shouldBe(['1.3', 'bar']);
        $this->find($element, $this->resolveOptions(['path' => '/doc/foo', 'multiple' => true, 'cast' => 'int']))->shouldBe([0, 1]);
    }

    public function it_can_sort_multiple_values_by_another_xpath()
    {
        $element = $this->createElement('
<doc>
    <foo>bar</foo>
    <sort><what>bar</what><value>2</value></sort>
    <foo>foo</foo>
    <sort><what>foo</what><value>1</value></sort>
</doc>');

        $this->find($element, $this->resolveOptions([
            'path'     => '/doc/foo',
            'multiple' => true,
            'sort'     => '/doc/sort[./what=./text()]/value',
        ])
        )->shouldBe(['foo', 'bar']);
    }

    public function it_can_sort_multiple_values()
    {
        $element = $this->createElement('<doc><foo>bar</foo><foo>1.3</foo></doc>');

        $this->find($element, $this->resolveOptions(['path' => '/doc/foo', 'multiple' => true, 'sort' => false]))->shouldBe(['bar', '1.3']);
    }

    public function it_raises_an_exception_in_case_of_multiple_values_but_a_single_was_expected()
    {
        $element = $this->createElement('<doc><foo>bar</foo><foo>1.3</foo></doc>');

        $this->shouldThrow(AmbigousValueException::class)->during('find', [$element, $this->resolveOptions(['path' => '/doc/foo'])]);
    }

    public function it_can_filter_single_values_with_a_callable()
    {
        $element = $this->createElement('<doc><foo>foo</foo></doc>');

        $this->find($element, $this->resolveOptions(['path' => '/doc/foo', 'condition' => function ($key, $value) {
            return 'foo' !== $value;
        }]))->shouldBe(null);
    }

    public function it_can_filter_multiple_values_with_a_callable()
    {
        $element = $this->createElement('<doc><foo>bar</foo><foo>1.3</foo></doc>');

        $this->find($element, $this->resolveOptions(['path' => '/doc/foo', 'multiple' => true, 'condition' => function ($key, $value) {
            return 'bar' !== $value;
        }]))->shouldBe(['1.3']);
    }

    public function it_throws_an_exception_in_case_of_not_nullable_option()
    {
        $element = $this->createElement('<doc><foo>bar</foo></doc>');

        $options = $this->resolveOptions(['path' => '/doc/bar', 'nullable' => false]);

        $this->shouldThrow(NoValueException::class)->during('find', [$element, $options]);
    }

    public function it_can_handle_nullable_values()
    {
        $element = $this->createElement('<doc><foo>bar</foo></doc>');

        $options = $this->resolveOptions(['path' => '/doc/bar', 'nullable' => true]);

        $this->find($element, $options)->shouldBe(null);
    }
}
