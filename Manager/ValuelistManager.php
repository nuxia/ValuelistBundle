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
     * @return string
     */
    private function getClassName()
    {
        return 'Nuxia\ValuelistBundle\Entity\Valuelist';
    }

    /**
     * @param string $locale
     * @param string $category
     * @param string $parent
     * @param array  $criteria
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
     * @param array $defaults
     *
     * @return \Nuxia\ValuelistBundle\Entity\Valuelist
     */
    public function create(array $defaults = array())
    {
        $class = $this->getClassname();
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
        $this->em->persist($valuelist);
        if ($andFlush === true) {
            $this->em->flush();
        }
    }

    /**
     * @param string $locale
     * @param string $category
     * @param string $parent
     * @param array  $criteria
     * @param string $type
     * @param array  $parameters
     *
     * @return array
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
     * @param string $locale
     * @param string $category
     * @param string $parent
     * @param array  $criteria
     * @param string $type
     * @param array  $parameters
     *
     * @return \Nuxia\ValuelistBundle\Entity\Valuelist[]
     */
    public function findByCriteria(
        $locale,
        $category,
        $parent = 'null',
        array $criteria,
        $type = 'default',
        array $parameters = array()
    ) {
        $criteria = $this->getCriteria($locale, $category, $parent, $criteria);

        return $this->getRepository()->findByCriteria($criteria, $type, $parameters);

    }
}
