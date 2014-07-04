<?php

namespace Atom\AuthenticationBundle\Tests\Token;

use Atom\AuthenticationBundle\Token\HmacToken;

class HmacTokenTest extends \PHPUnit_Framework_TestCase
{

    public function testNotAuthenticated()
    {
        $requestTime = $this->getMock('\DateTime');
        $hmacToken = new HmacToken($requestTime, 'foo', 'bar', 'foo-bar');

        $this->assertEquals($requestTime, $hmacToken->getRequestTime(), '->getRequestTime() returns the correct value.');
        $this->assertEquals('foo', $hmacToken->getContentType(), '->getContentType() returns the correct value.');
        $this->assertEquals('bar', $hmacToken->getRequestMethod(), '->getRequestMethod() returns the correct value.');
        $this->assertEquals('foo-bar', $hmacToken->getContent(), '->getContent() returns the correct value.');
    }

}
