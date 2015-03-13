<?php

namespace Nuxia\ValuelistBundle\Manager;

use Nuxia\ValuelistBundle\Entity\Valuelist;

interface ValuelistManagerInterface
{
    /**
     * @param  string $locale
     * @param  string $category
     * @param  string $parent
     * @param  string $type
     * @param  bool   $useCache
     *
     * @return array
     */
    public function getValuelist($locale, $category, $parent = 'null', $type = 'default', $cache = true);

    /**
     * @param  string $locale
     * @param  string $category
     * @param  string $code
     * @param  string $parent
     *
     * @return null|array
     */
    public function getValue($locale, $category, $code, $parent = 'null');

    //@TODO useCache doit utiliser findByCriteria pour eviter de l'exposer via l'interface
    /**
     * @param  string $locale
     * @param  string $category
     * @param  string $parent
     * @param  array  $criteria
     * @param  string $type
     * @param  array  $parameters
     *
     * @return Valuelist[]
     */
    public function findByCriteria($locale, $category, $parent = 'null', array $criteria = array(), $type = 'default', array $parameters = array());

    /**
     * @param  string $locale
     * @param  string $category
     * @param  string $code
     *
     * @return Valuelist|null
     */
    public function findOneByCriteria($locale, $category, $code);

    /**
     * @param  string $locale
     * @param  string $category
     * @param  string $parent
     * @param  array  $criteria
     * @param  string $type
     * @param  array  $parameters
     *
     * @return array
     */
    public function findStmtByCriteria($locale, $category, $parent = 'null', array $criteria, $type = 'default', array $parameters = array());
}
