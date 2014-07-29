<?php

namespace spec\DigitalOceanV2\Exception;

class ExceptionReaderSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith('foo', 123);
        $this->shouldHaveType('DigitalOceanV2\Exception\ExceptionReader');
    }

    function it_cannot_read_the_given_exception()
    {
        $this->beConstructedWith('{}');

        $this->getId()->shouldBeNull();
        $this->getMessage()->shouldReturn('Request not processed. ()');
        $this->getMessage(false)->shouldReturn('Request not processed.');
    }

    function it_can_read_the_exception()
    {
        $this->beConstructedWith('{"id":"not_found", "message":"The resource you were accessing could not be found."}', 404);

        $this->getId()->shouldReturn('not_found');
        $this->getMessage()->shouldReturn('[404] The resource you were accessing could not be found. (not_found)');
        $this->getMessage(false)->shouldReturn('The resource you were accessing could not be found.');
    }
}
