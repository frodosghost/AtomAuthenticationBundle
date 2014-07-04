<?php

namespace Atom\AuthenticationBundle\Tests\Entity;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class AccessKeyRepositoryFunctionalTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    protected function tearDown()
    {
        //$this->loadFixtures(array());

        $this->getContainer()->get('doctrine')->getConnection()->close();
        parent::tearDown();
    }

    public function testCreateNewLog()
    {
        $accessKey = $this->em
            ->getRepository('AtomAuthenticationBundle:AccessKey')
            ->createNewAccessKey();

        $this->assertInstanceOf('Atom\AuthenticationBundle\Entity\AccessKey', $accessKey,
            'Returns the correct instace for the Member.');

        $this->assertNull($accessKey->getId(), '->getId() is NULL because object has not been persisted.');

        $this->assertNotNull($accessKey->getPublicKey(), '->getPublicKey() is NOT NULL which means value has been set.');
        $this->assertEquals(25, strlen($accessKey->getPublicKey()), '->getPublicKey() is the correct length');

        $this->assertNotNull($accessKey->getPrivateKey(), '->getPrivateKey() is NOT NULL which means value has been set.');
        $this->assertEquals(45, strlen($accessKey->getPrivateKey()), '->getPrivateKey() is the correct length');
    }

}
