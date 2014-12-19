<?php

namespace Nuxia\ValuelistBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Nuxia\ValuelistBundle\Repository\ValuelistRepository;

abstract class AbstractManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return ValuelistRepository
     */
    public function getRepository()
    {
        return $this->entityManager->getRepository($this->getClassName());
    }

    /**
     * @return string
     */
    protected function getClassName()
    {
        return 'Nuxia\ValuelistBundle\Entity\Valuelist';
    }
}
