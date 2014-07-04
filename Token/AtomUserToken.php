<?php

namespace Atom\AuthenticationBundle\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class AtomUserToken extends AbstractToken
{
    /**
     * @var string
     */
    private $publicKey;

    /**
     * @var string
     */
    private $signature;

    /**
     * @var Atom\AuthenticationBundle\Token\HmacToken
     */
    private $hmacToken;


    public function __construct(array $roles = array())
    {
        parent::__construct($roles);

        // If the user has roles, consider it authenticated
        $this->setAuthenticated(count($roles) > 0);
    }

    /**
     * Get Credentials
     * 
     * @return string
     */
    public function getCredentials()
    {
        return '';
    }

    /**
     * Set Public Key
     * 
     * @param  string $publicKey
     * @return Atom\AuthenticationBundle\Token\AtomUserToken
     */
    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;

        return $this;
    }

    /**
     * Get Public Key
     * 
     * @return string
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * Set Signature
     * 
     * @param  string $publicKey
     * @return Atom\AuthenticationBundle\Token\AtomUserToken
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;

        return $this;
    }

    /**
     * Get Signature
     * 
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * Set HMAC Token
     * 
     * @param  string $publicKey
     * @return Atom\AuthenticationBundle\Token\AtomUserToken
     */
    public function setHmacToken(HmacToken $hmacToken)
    {
        $this->hmacToken = $hmacToken;

        return $this;
    }

    /**
     * Get HMAC Token
     * 
     * @return Atom\AuthenticationBundle\Token\HmacToken
     */
    public function getHmacToken()
    {
        return $this->hmacToken;
    }

}
