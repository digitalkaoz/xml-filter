<?php

namespace spec\Rs\XmlFilter\Filter;

use PhpSpec\ObjectBehavior;
use Rs\XmlFilter\Filter\Filter;
use Rs\XmlFilter\Filter\PostFilter;
use Rs\XmlFilter\Filter\ScalarFilter;

/**
 * @mixin PostFilter
 * @covers PostFilter
 */
class PostFilterSpec extends ObjectBehavior
{
    use FilterSpecTrait;

    public function let()
    {
        $this->injectManager();
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(PostFilter::class);
        $this->shouldHaveType(Filter::class);
    }

    public function it_calls_the_underlying_filter()
    {
        $element = $this->createElement('<xml><doc><foo>foo</foo><bar>bar</bar></doc><doc><foo>a</foo><bar>b</bar></doc></xml>');

        $this->find($element, $this->resolveOptions([
            'callable' => function ($data) {
                $data['foo'] = 'foo';

                return $data;
            },
            'real_filter'   => [
                'filter' => ScalarFilter::class,
                'path'   => '/xml/doc/foo', 'multiple' => true, 'sort' => true,
            ], ]
        ))->shouldBe([
            'a',
            'foo',
            'foo' => 'foo',
        ]);
    }
}
