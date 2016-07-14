<?php

namespace spec\Rs\XmlFilter\Console;

use PhpSpec\ObjectBehavior;
use Rs\XmlFilter\Console\FilterCommand;
use Rs\XmlFilter\XmlFilter;
use Symfony\Component\Console\Command\Command;

/**
 * @mixin FilterCommand
 * @covers FilterCommand
 */
class FilterCommandSpec extends ObjectBehavior
{
    public function let(XmlFilter $filter)
    {
        $this->beConstructedWith($filter);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(FilterCommand::class);
        $this->shouldHaveType(Command::class);
    }
}
