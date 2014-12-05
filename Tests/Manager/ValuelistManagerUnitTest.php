<?php

namespace Nuxia\ValuelistBundle\Tests\Manager;

use Nuxia\ValuelistBundle\Manager\ValuelistManager;
use Nuxia\ValuelistBundle\Tests\Repository\FakeValuelistRepository;

class ValuelistManagerUnitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ValuelistManager
     */
    protected $valuelistManager;

    protected $entityManager;

    protected function setUp()
    {
        parent::setUp();

        $this->valuelistManager = new ValuelistManager();

        $this->entityManager = $this->entityManager = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->setMethods(array('getRepository'))
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->entityManager->expects($this->any())
            ->method('getRepository')
            ->with($this->equalTo('Nuxia\ValuelistBundle\Entity\Valuelist'))
            ->will($this->returnValue(new FakeValuelistRepository()))
        ;
    }

    public function testCreate()
    {
        $result = $this->valuelistManager->create();
        $this->assertInstanceOf('Nuxia\ValuelistBundle\Entity\Valuelist', $result);
    }

    /**
     * @dataProvider getCriteriaDataCall
     */
    public function testGetCriteria($locale, $category, $parent, $criteria, $expected)
    {
        $args = func_get_args();
        end($args);
        unset($args[key($args)]);

        $reflection = new \ReflectionObject($this->valuelistManager);
        $method = $reflection->getMethod('getCriteria');
        $method->setAccessible(true);

        if ($expected === '\Exception') {
            try {
                $method->invokeArgs($this->valuelistManager, $args);

                $this->assertTrue(false, sprintf('Exception not catch with %s %s %s %s, expected : %s',
                    $locale, $category, $parent, $criteria, $expected
                ));

            } catch (\Exception $e) {
                $this->assertInstanceOf($expected, $e);
            }
        } else {
            $result = $method->invokeArgs($this->valuelistManager, $args);
            $this->assertEquals($result, $expected);

        }
    }

    public function getCriteriaDataCall()
    {
        $expected = array();

        //Explicit key for readability
        $expected[0] = array('category' => 'foo', 'language' => array('default', 'fr'));
        $expected[1] = '\Exception';

        return array(
            array('fr', 'foo', 'null', array(), $expected[0]),
            array(true, false, true, 'foo', $expected[1]),
            array('foo', 'bar', 'bar', 'foo', $expected[1])
        );
    }

    public function testGetEntityName()
    {
        $result = $this->valuelistManager->getEntityName();
        $this->assertEquals('Nuxia\ValuelistBundle\Entity\Valuelist', $result);
    }

    /**
     * @dataProvider valuelistDataCall
     */
    public function testGetValuelist($locale, $category, $parent, $type, $usecache, $expected)
    {
        $this->valuelistManager->setEntityManager($this->entityManager);

        if ($expected === '\Exception') {
            try {
                $this->valuelistManager->getValuelist($locale, $category, $parent, $type, $usecache, $expected);

                $this->assertTrue(false, sprintf('Exception not catch with %s %s %s %s %s, expected : %s',
                        $locale, $category, $parent, $type, $usecache, $expected
                    ));
            } catch (\Exception $e) {
                $this->assertInstanceOf($expected, $e);
            }
        } else {
            $result = $this->valuelistManager->getValuelist($locale, $category, $parent, $type, $usecache, $expected);
            $this->assertEquals($result, $expected);
        }
    }

    public function valuelistDataCall()
    {
        $expected = array();

        //Explicit key to readability
        $expected[0] = array('code1' => array('label' => 'label1'), 'code2' => array('label' => 'label12'));
        $expected[1] = array('code1' => array('label' => 'label1'), 'code2' => array('label' => 'label2'));
        $expected[2] = array('default1' => array('label' => 'labelen'), 'default2' => array('label' => 'label-default'));
        $expected[3] = '\Exception';

        return array(
            //Cached
            array('fr', 'category1', 'null', 'default', true, $expected[0]),
            array('fr', 'category3', 'parent2', 'default', true, $expected[1]),
            array('fr', 'category3', 'parent2', 'default', true, $expected[1]),
            array('en', 'category1', 'null', 'default', true, $expected[1]),
            array('en', 'category2', 'null', 'default', true, $expected[2]),

            //Not cached
            array('fr', 'category1', 'null', 'default', false, $expected[0]),
            array('fr', 'category3', 'parent2', 'default', false, $expected[1]),
            array('fr', 'category3', 'parent2', 'default', false, $expected[1]),
            array('en', 'category1', 'null', 'default', false, $expected[1]),
            array('en', 'category2', 'null', 'default', false, $expected[2]),

            //Fail cached
            array('fr', 'category1', 'failure', 'default', false, $expected[3]),
            array('fr', 'failure', 'null', 'default', false, $expected[3]),
            array('failure', 'category1', 'null', 'default', false, $expected[3]),

            //Fail not cached
            array('fr', 'category1', 'failure', 'default', false, $expected[3]),
            array('fr', 'failure', 'null', 'default', false, $expected[3]),
            array('failure', 'category1', 'null', 'default', false, $expected[3]),
        );
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}
