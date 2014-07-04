<?php

namespace Atom\AuthenticationBundle\Tests\Firewall;

use Atom\AuthenticationBundle\Firewall\AtomAuthListener;
use Atom\AuthenticationBundle\Token\AtomUserToken;
use Atom\AuthenticationBundle\Token\HmacToken;
use Symfony\Component\HttpFoundation\Response;

class AtomAuthListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $securityContext;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $authenticationManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $responseEvent;

    /**
     * @var Atom\AuthenticationBundle\Token\HmacToken
     */
    private $hmacToken;


    public function setUp()
    {
        $this->securityContext = $this->getMock('Symfony\Component\Security\Core\SecurityContextInterface');
        $this->authenticationManager = $this->getMock('Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface', array('authenticate'));
        $this->request = $this->getMockForAbstractClass('Symfony\Component\HttpFoundation\Request');

        $this->responseEvent = $this->getMockBuilder('\Symfony\Component\HttpKernel\Event\GetResponseEvent')->disableOriginalConstructor()->getMock();
        $this->responseEvent->expects($this->once())->method('getRequest')->will($this->returnValue($this->request));

        $dateTime = new \DateTime();
        $dateTime->setTimezone(new \DateTimeZone('UTC'));
        $dateTime->setTimestamp(0);

        $this->hmacToken = new HmacToken($dateTime, null, 'GET', '');

        
    }

    public function testForbidden()
    {
        $atomListener = new AtomAuthListener($this->securityContext, $this->authenticationManager);

        $response = new Response();
        $response->setStatusCode(403);
        $this->responseEvent->expects($this->once())->method('setResponse')->with($response);

        $result = $atomListener->handle($this->responseEvent);
    }

    public function testUnauthorized()
    {
        $token = new AtomUserToken();
        $token->setPublicKey('foo')
            ->setSignature('bar')
            ->setHmacToken($this->hmacToken);

        $exceptionMock = $this->getMock('\Symfony\Component\Security\Core\Exception\AuthenticationException', array('getMessage'), array('Unauthorized'));

        $this->authenticationManager->expects($this->once())->method('authenticate')->with($token)->will($this->throwException($exceptionMock));
        $atomListener = new AtomAuthListener($this->securityContext, $this->authenticationManager);

        $this->request->headers->add(array('Authorization' => 'Atom foo:bar'));

        $response = new Response();
        $response->setStatusCode(401);
        $this->responseEvent->expects($this->once())->method('setResponse')->with($response);

        $result = $atomListener->handle($this->responseEvent);
    }

    public function testReturnToken()
    {
        $token = new AtomUserToken();
        $token->setPublicKey('foo')
            ->setSignature('bar')
            ->setHmacToken($this->hmacToken);

        $tokenMock2 = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $this->authenticationManager->expects($this->once())->method('authenticate')->with($token)->will($this->returnValue($tokenMock2));
        $this->securityContext->expects($this->once())->method('setToken')->with($tokenMock2);

        $this->request->headers->add(array('Authorization' => 'Atom foo:bar'));

        $atomListener = new AtomAuthListener($this->securityContext, $this->authenticationManager);

        $result = $atomListener->handle($this->responseEvent);
    }

    public function testReturnResponse()
    {
        $token = new AtomUserToken();
        $token->setPublicKey('foo')
            ->setSignature('bar')
            ->setHmacToken($this->hmacToken);

        $response = new Response();
        $this->authenticationManager->expects($this->once())->method('authenticate')->with($token)->will($this->returnValue($response));
        $this->responseEvent->expects($this->once())->method('setResponse')->with($response);

        $this->request->headers->add(array('Authorization' => 'Atom foo:bar'));

        $atomListener = new AtomAuthListener($this->securityContext, $this->authenticationManager);

        $result = $atomListener->handle($this->responseEvent);
    }

}
