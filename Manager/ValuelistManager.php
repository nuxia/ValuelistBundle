<?php

namespace Nuxia\ValuelistBundle\Manager;

use Nuxia\ValuelistBundle\Entity\Valuelist;

class ValuelistManager extends AbstractManager implements ValuelistManagerInterface
{
    /**
     * @var array
     */
    protected $cache = array();

    /**
     * @param  string $locale
     * @param  string $category
     * @param  string $parent
     * @param  array  $criteria
     *
     * @return array
     */
    protected function getCriteria($locale, $category, $parent = 'null', array $criteria = array())
    {
        $criteria['category'] = $category;
        $criteria['language'] = array('default', $locale);
        if ($parent !== 'null') {
            $criteria['parent'] = array('code' => $parent);
        }

        return $criteria;
    }

    /**
     * @param  array $defaults
     *
     * @return Valuelist
     */
    public function create(array $defaults = array())
    {
        $class = $this->getClassname();
        /** @var Valuelist $valuelist */
        $valuelist = new $class();
        $valuelist->fromArrayOrObject($defaults);

        return $valuelist;
    }

    /**
     * {@inheritdoc}
     */
    public function getValuelist($locale, $category, $parent = 'null', $type = 'default', $useCache = true)
    {
        if ($useCache === false || !isset($this->cache[$category][$locale][$parent])) {
            $valuelist = $this->findStmtByCriteria($locale, $category, $parent, array(), $type);
            if ($useCache === false) {
                return $valuelist;
            }

            $this->cache[$category][$locale][$parent] = $valuelist;
        }

        return $this->cache[$category][$locale][$parent];
    }

    /**
     * {@inheritdoc}
     */
    public function getValue($locale, $category, $code, $parent = 'null')
    {
        $valuelist = $this->getValuelist($locale, $category, $parent);

        if (array_key_exists($code, $valuelist)) {
            return $valuelist[$code];
        } else {
            return null;
        }
    }

    /**
     * @param Valuelist $valuelist
     * @param bool      $andFlush
     */
    public function persist(Valuelist $valuelist, $andFlush = true)
    {
        $this->entityManager->persist($valuelist);

        if ($andFlush === true) {
            $this->entityManager->flush();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function findStmtByCriteria(
        $locale,
        $category,
        $parent = 'null',
        array $criteria,
        $type = 'default',
        array $parameters = array()
    ) {
        $criteria = $this->getCriteria($locale, $category, $parent, $criteria);

        return $this->getRepository()->findStmtByCriteria($criteria, $type, $parameters);
    }

    /**
     * {@inheritDoc}
     */
    public function findByCriteria($locale, $category, $parent = 'null', array $criteria = array(), $type = 'default', array $parameters = array())
    {
        $criteria = $this->getCriteria($locale, $category, $parent, $criteria);

        return $this->getRepository()->findByCriteria($criteria, $type, $parameters);

    }

    /**
     * {@inheritDoc}
     */
    public function findOneByCriteria($locale, $category, $code)
    {
        $criteria = $this->getCriteria($locale, $category);
        $criteria['code'] = $code;
        return $this->getRepository()->findOneByCriteria($criteria);

    }
}
