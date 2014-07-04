<?php

namespace Atom\AuthenticationBundle\Tests\Token;

use Atom\AuthenticationBundle\Token\AtomUserToken;

class AtomUserTokenTest extends \PHPUnit_Framework_TestCase
{

    public function testNotAuthenticated()
    {
        $atomUserToken = new AtomUserToken();

        $this->assertFalse($atomUserToken->isAuthenticated());
    }

    public function testAuthenticated()
    {
        $atomUserToken = new AtomUserToken(array('foo'));

        $this->assertTrue($atomUserToken->isAuthenticated());
    }

    public function testSetHmacToken()
    {
        $mockHmacToken = $this->getMockBuilder('Atom\AuthenticationBundle\Token\HmacToken')->disableOriginalConstructor()->getMock();

        $atomUserToken = new AtomUserToken();
        $atomUserToken->setHmacToken($mockHmacToken);

        $this->assertEquals($mockHmacToken, $atomUserToken->getHmacToken(), '->getHmacToken() returns the correct HmacToken class');
    }
}
