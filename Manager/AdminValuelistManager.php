<?php

namespace Nuxia\ValuelistBundle\Manager;

use Nuxia\ValuelistBundle\Entity\Valuelist;

class AdminValuelistManager extends ValuelistManager implements
    AdminValuelistManagerInterface
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
     * @param Valuelist $valuelist
     * @param bool      $andFlush
     */
    public function delete(Valuelist $valuelist, $andFlush = true)
    {
        $this->entityManager->remove($valuelist);
        if ($andFlush === true) {
            $this->entityManager->flush();
        }
    }

    /**
     * @param array  $criteria
     * @param string $type
     * @param array  $parameters
     *
     * @return mixed
     */
    public function getControllerObject(array $criteria, $type = 'default', array $parameters = array())
    {
        return $this->getRepository()->findOneByCriteria($criteria, $type, $parameters);
    }

    /**
     * @param $action
     * @param $category
     *
     * @return bool
     */
    public function isActionExistsOnCategory($action, $category)
    {
        return in_array($action, $this->categories[$category]);
    }
}
