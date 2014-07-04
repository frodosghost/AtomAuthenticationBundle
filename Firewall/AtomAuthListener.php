<?php

namespace Atom\AuthenticationBundle\Firewall;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;

use Atom\AuthenticationBundle\Token\AtomUserToken;
use Atom\AuthenticationBundle\Token\HmacToken;

/**
 * AtomAuthListener
 */
class AtomAuthListener implements ListenerInterface
{
    /**
     * @var Symfony\Component\Security\Core\SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface
     */
    private $authenticationManager;

    public function __construct(SecurityContextInterface $securityContext, AuthenticationManagerInterface $authenticationManager)
    {
        $this->securityContext = $securityContext;
        $this->authenticationManager = $authenticationManager;
    }

    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($request->headers->has('Authorization')) {

            $authorizationRegex = '/Atom (?P<public_key>[A-Za-z0-9]+):(?P<signature>[A-Za-z0-9\/\=+]+)/';

            if (preg_match($authorizationRegex, $request->headers->get('authorization'), $matches)) {

                // Add Date information to request from headers
                $requestTime = new \DateTime();
                if ($request->headers->has('Date')) {
                    $utc_date = date("U",strtotime($request->headers->get('Date')));
                    $requestTime->setTimestamp($utc_date);
                } else {
                    $requestTime->setTimestamp($request->server->get('REQUEST_TIME'));
                }
                $requestTime->setTimezone(new \DateTimeZone('UTC'));

                $token = new AtomUserToken();
                $token->setPublicKey($matches['public_key']);
                $token->setSignature($matches['signature']);

                $hmacToken = new HmacToken(
                    $requestTime,
                    $request->headers->get('Content-Type'),
                    $request->getMethod(),
                    $request->getContent()
                );

                $token->setHmacToken($hmacToken);

                try {
                    $returnValue = $this->authenticationManager->authenticate($token);

                    if ($returnValue instanceof TokenInterface) {
                        return $this->securityContext->setToken($returnValue);
                    } else if ($returnValue instanceof Response) {
                        return $event->setResponse($returnValue);
                    }
                } catch (AuthenticationException $e) {
                    // LOGGER?
                    $response = new Response();
                    $response->setStatusCode(401, ($e instanceof AuthenticationException) ? $e->getMessage() : null);
                    $event->setResponse($response);
                    return;
                }
            }
        }

        $response = new Response();
        $response->setStatusCode(403);
        $event->setResponse($response);
    }

}
