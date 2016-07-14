<?php

namespace spec\Rs\XmlFilter\Filter;

use PhpSpec\ObjectBehavior;
use Prophecy\Exception\Prediction\FailedPredictionException;
use Rs\XmlFilter\Exception\ReferenceException;
use Rs\XmlFilter\Filter\AggregateFilter;
use Rs\XmlFilter\Filter\Filter;
use Rs\XmlFilter\Filter\ReferenceFilter;

/**
 * @mixin ReferenceFilter
 * @covers ReferenceFilter
 */
class ReferenceFilterSpec extends ObjectBehavior
{
    use FilterSpecTrait;

    public function let()
    {
        $this->injectManager();
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ReferenceFilter::class);
        $this->shouldHaveType(Filter::class);
    }

    public function it_raises_an_exception_if_the_reference_could_not_be_resolved()
    {
        $element = $this->createElement('<doc><foo>bazz</foo><bar>bar</bar></doc>');

        $options = $this->resolveOptions([
            'reference' => '/doc/lolcat',
            'value'     => '/doc/bar',
        ]);

        $this->shouldThrow(ReferenceException::class)->during('find', [$element, $options]);
    }

    public function it_filters_only_if_the_reference_could_be_resolved()
    {
        $element = $this->createElement('<doc><foo>bazz</foo><bar>bar</bar></doc>');

        $options = $this->resolveOptions([
            'reference' => '/doc/foo[text()="bazz"]',
            'value'     => '/doc/bar',
        ]);

        $this->find($element, $options)->shouldBe('bar');
    }

    public function it_can_use_the_reference_as_value_if_non_provided()
    {
        $element = $this->createElement('<doc><foo>bazz</foo><bar>bar</bar></doc>');

        $options = $this->resolveOptions([
            'reference' => '/doc/foo[text()="bazz"]',
        ]);

        $this->find($element, $options)->shouldBe('bazz');
    }

    public function it_can_accept_another_xpath_as_error_element()
    {
        $element = $this->createElement('<doc><foo>bazz</foo><bar>bar</bar></doc>');

        $options = $this->resolveOptions([
            'reference'    => '/doc/lolcat',
            'value'        => '/doc/bar',
            'element'      => '/doc/foo',
        ]);

        //sadly we cant do further checks on exceptions with phpspec, so we do it by hand
        try {
            $this->find($element, $options);
        } catch (ReferenceException $e) {
            if ('bazz' !== (string) $e->getElement()) {
                throw new FailedPredictionException('error element is not the expected one');
            }
        }
    }

    public function it_can_execute_complex_filters_as_value()
    {
        $element = $this->createElement('<doc><foo>bazz</foo><bar>bar</bar></doc>');

        $options = $this->resolveOptions([
            'reference' => '/doc/foo[text()="bazz"]',
            'value'     => [
                'filter'   => AggregateFilter::class,
                'mappings' => [
                    'values' => ['path' => '/doc/bar', 'multiple' => true],
                ],
            ],
        ]);

        $this->find($element, $options)->shouldBe(['values' => ['bar']]);
    }
}
