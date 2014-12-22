<?php

namespace spec\DigitalOceanV2\Exception;

class ResponseExceptionSpec extends \PhpSpec\ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Exception\ResponseException');
    }

    public function it_returns_created_an_exception()
    {
        $this->create('foo')->shouldReturnAnInstanceOf('DigitalOceanV2\Exception\ResponseException');

        $this->getErrorMessage()->shouldReturn('Request not processed.');
        $this->getErrorMessage(true)->shouldReturn('Request not processed. ()');
        $this->getErrorId()->shouldReturn('');
        $this->getMessage()->shouldReturn('Request not processed. ()');
        $this->getCode()->shouldReturn(0);
        $this->getPrevious()->shouldBeNull();
    }

    // more specs should be written here when `beConstructedThrough` will be released
    // @see https://github.com/phpspec/phpspec/pull/335
    // @see https://github.com/phpspec/phpspec/pull/336
}
