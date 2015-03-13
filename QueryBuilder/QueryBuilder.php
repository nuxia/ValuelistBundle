<?php

namespace Nuxia\ValuelistBundle\QueryBuilder;

use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;

class QueryBuilder extends DoctrineQueryBuilder
{
    /**
     * @param $entityName
     * @param $alias
     * @param null $indexBy
     */
    public function __construct($entityName, $alias, $indexBy = null)
    {
        $this->setSelect($alias);
        $this->from($entityName, $alias, $indexBy);
    }
}
