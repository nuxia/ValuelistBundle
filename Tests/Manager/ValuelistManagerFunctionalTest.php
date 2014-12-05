<?php

namespace Nuxia\ValuelistBundle\Tests\Manager;

use Nuxia\ValuelistBundle\Manager\ValuelistManager;
use Nuxia\ValuelistBundle\Tests\Repository\FakeValuelistRepository;

class ValuelistManagerFunctionalTest extends \PHPUnit_Framework_TestCase
{
    private $valuelistManager;
    private $repository = null;

    public function setUp()
    {
        $this->repository = new FakeValuelistRepository();
        $emStub = $this->getMock('\Doctrine\ORM\EntityManagerInterface');
        $emStub->expects($this->any())->method('getRepository')->will($this->returnValue($this->repository));
        $this->valuelistManager = new ValuelistManager();
        $this->valuelistManager->setEntityManager($emStub);
    }

    /** @test */
    public function can_return_entire_category()
    {
        $notExistingLang = $this->valuelistManager->getValuelist('fake', 'category1');
        $this->assertCount(0, $notExistingLang);
        $notExistingCategory = $this->valuelistManager->getValuelist('fr', 'fake');
        $this->assertCount(0, $notExistingCategory);
        $existing = $this->valuelistManager->getValuelist('fr', 'category1');
        $this->assertGreaterThan(0, $existing);
    }

    /** @test */
    public function returns_null_label_for_missing_locale()
    {
        $notExistingLang = $this->valuelistManager->getValue('fake', 'category1', 'code1');
        $this->assertNull($notExistingLang);
    }

    /** @test */
    public function returns_null_label_for_missing_category()
    {
        $notExistingCategory = $this->valuelistManager->getValue('fr', 'fake', 'code1');
        $this->assertNull($notExistingCategory);
    }

    /** @test */
    public function returns_null_label_for_missing_code()
    {
        $notExistingCode = $this->valuelistManager->getValue('fr', 'category1', 'fake');
        $this->assertNull($notExistingCode);
    }

    /** @test */
    public function returns_label_for_existing_code()
    {
        $existing = $this->valuelistManager->getValue('fr', 'category1', 'code1');
        $this->assertEquals('label1', $existing['label']);
    }

    /** @test */
    public function caches_categories()
    {
        $this->valuelistManager->getValuelist('fr', 'category1');
        $this->valuelistManager->getValuelist('fr', 'category1');
        $this->assertEquals(1, $this->repository->getQueriesCount());
    }

    /** @test */
    public function caches_labels()
    {
        $this->valuelistManager->getValue('fr', 'category1', 'code1');
        $this->valuelistManager->getValue('fr', 'category1', 'code1');
        $this->assertEquals(1, $this->repository->getQueriesCount());
    }

    /** @test */
    public function caches_entire_category_when_one_label_is_requested()
    {
        $this->valuelistManager->getValue('fr', 'category1', 'code1');
        $this->valuelistManager->getValuelist('fr', 'category1');
        $this->assertEquals(1, $this->repository->getQueriesCount());
    }

    /** @test */
    public function requesting_a_label_uses_the_category_cache()
    {
        $this->valuelistManager->getValuelist('fr', 'category1');
        $this->valuelistManager->getValue('fr', 'category1', 'code1');
        $this->assertEquals(1, $this->repository->getQueriesCount());
    }

    /** @test */
    public function holds_multiple_languages_in_cache()
    {
        $this->valuelistManager->getValuelist('fr', 'category1');
        $this->valuelistManager->getValuelist('en', 'category1');
        $this->valuelistManager->getValuelist('fr', 'category1');
        $this->assertEquals(2, $this->repository->getQueriesCount());
    }

    /** @test */
    public function adds_labels_from_default_language()
    {
        $merge = $this->valuelistManager->getValuelist('fr', 'category2');
        $this->assertCount(3, $merge);
    }

    /** @test */
    public function returns_label_from_default_language_when_missing()
    {
        $noinlang = $this->valuelistManager->getValue('fr', 'category2', 'default1');
        $this->assertEquals('label-default', $noinlang['label']);
    }

    /** @test */
    public function uses_the_requested_language_over_the_default_language()
    {
        $langBeforeDefault = $this->valuelistManager->getValue('en', 'category2', 'default1');
        $this->markTestIncomplete();
        $this->assertEquals('label-en', $langBeforeDefault['label']);
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}
