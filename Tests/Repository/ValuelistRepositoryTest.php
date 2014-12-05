<?php

namespace Nuxia\ValuelistBundle\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

//@TODO a tester
abstract class ValuelistRepositoryTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager;
     */
    private $em;

    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
    }

    public function testQueryValuelists()
    {
        $valuelist = $this->em->getRepository('NuxiaValuelistBundle:Valuelist')->getQueryBuilderByCriteria(
            array('category' => 'abr_type', 'parent' => array('code' => 'ESGIE')), 'default', array('limit' => 3)
        )->getQuery()->getResult();
        $this->assertCount(3, $valuelist);
    }

    protected function tearDown()
    {
        $this->em->close();
        parent::tearDown();
    }
}
