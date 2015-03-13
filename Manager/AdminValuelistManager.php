<?php

namespace Nuxia\ValuelistBundle\Manager;

use Nuxia\ValuelistBundle\Entity\Valuelist;

class AdminValuelistManager extends ValuelistManager implements AdminValuelistManagerInterface
{
    /**
     * @var array
     */
    protected $categoryMap;

    /**
     * @param array $categoryMap
     */
    public function setCategoryMap(array $categoryMap)
    {
        $this->categoryMap = $categoryMap;
    }

    /**
     * {@inheritDoc}
     */
    public function delete(Valuelist $valuelist, $andFlush = true)
    {
        $this->entityManager->remove($valuelist);
        if ($andFlush === true) {
            $this->entityManager->flush();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getCategoryMap()
    {
        return $this->categoryMap;
    }

    /**
     * {@inheritDoc}
     */
    public function isActionExistsOnCategory($action, $category)
    {
        return in_array($action, $this->categoryMap[$category]);
    }
}
