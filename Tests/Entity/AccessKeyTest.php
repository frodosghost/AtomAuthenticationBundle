<?php

namespace Atom\AuthenticationBundle\Tests\Entity;

use Atom\AuthenticationBundle\Entity\AccessKey;

/**
 * AccessKeyTest
 *
 * @author James Rickard <james@frodosghost.com>
 */
class AccessKeyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test onCreate Behaviour
     */
    public function testOnCreate()
    {
        $accessKey = new AccessKey();

        $this->assertNull($accessKey->getUpdatedAt(), '->getUpdatedAt() returns NULL is the onCreate has not been called.');
        $this->assertNull($accessKey->getCreatedAt(), '->getCreatedAt() returns NULL is the onCreate has not been called.');

        $accessKey->onCreate();

        $this->assertInstanceOf('\DateTime', $accessKey->getUpdatedAt(), '->getUpdatedAt() returns DateTime instance when onCreate is called.');
        $this->assertInstanceOf('\DateTime', $accessKey->getCreatedAt(), '->getCreatedAt() returns DateTime instance when onCreate is called.');
    }

    /**
     * Test onUpdate Behaviour
     */
    public function testOnUpdate()
    {
        $accessKey = new AccessKey();

        $this->assertNull($accessKey->getUpdatedAt(), '->getUpdatedAt() returns NULL when onUpdate has not been called.');
        $this->assertNull($accessKey->getCreatedAt(), '->getCreatedAt() returns NULL when onUpdate has not been called.');

        $accessKey->onUpdate();

        $this->assertInstanceOf('\DateTime', $accessKey->getUpdatedAt(), '->getUpdatedAt() returns DateTime instance when onUpdate is called.');
        $this->assertNull($accessKey->getCreatedAt(), '->getCreatedAt() has not been updated when onUpdate is called.');
    }

}
