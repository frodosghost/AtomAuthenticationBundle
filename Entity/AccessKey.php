<?php

namespace Atom\AuthenticationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AccessKey
 */
class AccessKey
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $public_key;

    /**
     * @var string
     */
    private $private_key;

    /**
     * @var integer
     */
    private $status = 1;

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * @var \DateTime
     */
    private $updated_at;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set public_key
     *
     * @param integer $public_key
     * @return AccessKey
     */
    public function setPublicKey($public_key)
    {
        $this->public_key = $public_key;
    
        return $this;
    }

    /**
     * Get public_key
     *
     * @return string 
     */
    public function getPublicKey()
    {
        return $this->public_key;
    }

    /**
     * Set private_key
     *
     * @param string $private_key
     * @return AccessKey
     */
    public function setPrivateKey($private_key)
    {
        $this->private_key = $private_key;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->private_key;
    }

    /**
     * Set status
     *
     * @param integer    $status
     * @return AccessKey
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return AccessKey
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    
        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return AccessKey
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
    
        return $this;
    }

    /**
     * Get updated_at
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @ORM\PrePersist
     */
    public function onCreate()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @ORM\PreUpdate
     */
    public function onUpdate()
    {
        $this->setUpdatedAt(new \DateTime());
    }

}
