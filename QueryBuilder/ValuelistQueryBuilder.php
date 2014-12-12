<?php

namespace Nuxia\ValuelistBundle\QueryBuilder;

//@TODO not used yet
class ValuelistQueryBuilder extends QueryBuilder
{
    /**
     * @param null $indexBy
     */
    public function __construct($indexBy = null)
    {
        parent::__construct('Nuxia\ValuelistBundle\Entity\Valuelist', 'vls', $indexBy);
        $this->addOrderBy('vls.label');
    }

    /**
     * @param bool $withSelect
     *
     * @return ValuelistQueryBuilder
     */
    public function joinParent($withSelect = false)
    {
        $this->leftJoin('vls.parent', 'vls_par');
        if ($withSelect === true) {
            $this->addSelect('vls_par');
        }

        return $this;
    }

    /**
     * @return ValuelistQueryBuilder
     */
    public function joinSerializer()
    {
        $this->leftJoin('vls.children', 'vls_chd', 'WITH', 'vls.language = vls_chd.language');
        $this->leftJoin('vls_chd.children', 'vls_chd_chd');
        $this->addSelect('vls_chd', 'vls_chd_chd');

        return $this;
    }

    /**
     *
     */
    public function byCategoryAndLanguage($category, $language = null)
    {
        $this->andWhere('vls_category = :category');

    }

    /**
     * @param $field
     * @param $value
     *
     * @return ValuelistQueryBuilder
     */
    public function filterBy($field, $value)
    {
        $this->andWhere('vls.' . $field . ' = :' . $field);
        $this->setParameter($field, $value);

        return $this;
    }

    /**
     * @param ValuelistQueryBuilder
     *
     * @return ValuelistQueryBuilder
     */
    public function filterByLanguage($language = null)
    {
        $languages = array('default');
        if ($language !== null) {
            $languages[] = $language;
        }
        $this->andWhere('vls_category IN (:languages)');
        $this->setParameter('languages', $languages);

        return $this;
    }
} 