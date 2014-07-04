<?php

namespace Atom\AuthenticationBundle\Token;

class HmacToken
{

    /**
     * @var \DateTime
     */
    private $requestTime;

    /**
     * @var string
     */
    private $contentType;

    /**
     * @var string
     */
    private $requestMethod;

    /**
     * @var string
     */
    private $content;


    /**
     * Constructor
     * 
     * @param \DateTime $requestTime
     * @param string    $contentType
     * @param string    $requestMethod
     * @param string    $content
     */
    public function __construct(\DateTime $requestTime, $contentType, $requestMethod, $content)
    {
        $this->requestTime = $requestTime;
        $this->contentType = $contentType;
        $this->requestMethod = $requestMethod;
        $this->content = $content;
    }

    public function getRequestTime()
    {
        return $this->requestTime;
    }

    public function getContentType()
    {
        return $this->contentType;
    }

    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    public function getContent()
    {
        return $this->content;
    }

}
