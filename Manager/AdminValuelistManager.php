<?php

namespace Nuxia\ValuelistBundle\Manager;

use Nuxia\ValuelistBundle\Entity\Valuelist;

class AdminValuelistManager extends ValuelistManager implements AdminValuelistManagerInterface
{
    /**
     * @var array
     */
    protected $categories;

    /**
     * @param array $categories
     */
    public function setCategories(array $categories)
    {
        $this->categories = $categories;
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
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * {@inheritDoc}
     */
    public function isActionExistsOnCategory($action, $category)
    {
        return in_array($action, $this->categories[$category]);
    }
}
