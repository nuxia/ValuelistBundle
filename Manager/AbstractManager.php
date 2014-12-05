<?php

namespace Nuxia\ValuelistBundle\Manager;

abstract class AbstractManager
{
    /**
     * @var ObjectManager
     */
    protected $entityManager;

    /**
     * @param ObjectManager $entityManager
     */
    public function setEntityManager(ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository()
    {
        return $this->entityManager->getRepository($this->getClassName());
    }
}
