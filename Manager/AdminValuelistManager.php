<?php

namespace Nuxia\ValuelistBundle\Manager;

use Nuxia\Component\Doctrine\Manager\ControllerManagerInterface;
use Nuxia\ValuelistBundle\Entity\Valuelist;

class AdminValuelistManager extends ValuelistManager implements
    ControllerManagerInterface,
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
        $this->em->remove($valuelist);
        if ($andFlush === true) {
            $this->em->flush();
        }
    }

    /**
     * @param array  $criteria
     * @param string $type
     * @param array  $parameters
     *
     * @return mixed
     */
    public function getPaginatorTarget(array $criteria, $type = 'list', array $parameters = array())
    {
        return $this->getRepository()->getQueryByCriteria($criteria, $type, $parameters);
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
