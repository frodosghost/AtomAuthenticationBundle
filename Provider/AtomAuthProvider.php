<?php

namespace Atom\AuthenticationBundle\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\NonceExpiredException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use Atom\AuthenticationBundle\Token\AtomUserToken;
use Atom\AuthenticationBundle\Token\HmacToken;
use Atom\AuthenticationBundle\Entity\AccessKey;

/**
 * AtomAuthProvider
 */
class AtomAuthProvider implements AuthenticationProviderInterface
{
    private $userProvider;

    private $cacheDir;

    private $lifetime;

    public function __construct(UserProviderInterface $userProvider, $cacheDir, $lifetime)
    {
        $this->userProvider = $userProvider;
        $this->cacheDir     = $cacheDir;
        $this->lifetime     = $lifetime;
    }

    public function authenticate(TokenInterface $token)
    {
        $user = $this->userProvider->loadUserByPublicKey($token->getPublicKey());

        if ($user && $this->validateDigest($token, $user->getAccessKey())) {
            $authenticatedToken = new AtomUserToken($user->getRoles());
            $authenticatedToken->setUser($user);

            return $authenticatedToken;
        }

        throw new AuthenticationException('AtomAuth Authorization failed.');
    }

    protected function validateDigest(AtomUserToken $atomUserToken, AccessKey $accessKey)
    {
        $hmacToken = $atomUserToken->getHmacToken();
        $privateKey = $accessKey->getPrivateKey();

        // Expire timestamp after 5 minutes
        if (time() - $hmacToken->getRequestTime()->getTimestamp() > $this->getLifetime()) {
            throw new AuthenticationException('The AtomAuth authentication failed.', 'Repeat attack detected.');
        }

        $timestamp = $hmacToken->getRequestTime()->format('c');

        $digest = $timestamp ."\n". $hmacToken->getRequestMethod() ."\n". $hmacToken->getContentType() ."\n". md5($hmacToken->getContent());

        // Validate Secret
        $expected = base64_encode(hash_hmac('sha1', $digest, $privateKey, TRUE));

        return $atomUserToken->getSignature() === $expected;
    }

    /**
     * @param  TokenInterface $token
     * @return Boolean
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof AtomUserToken;
    }

    public function getLifetime()
    {
        return $this->lifetime;
    }

}
