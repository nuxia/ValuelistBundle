<?php

namespace Nuxia\ValuelistBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\ParameterBag;

class ValuelistRepository extends BaseEntityRepository
{
    /**
     * @param $join
     * @return QueryBuilder
     */
    protected function initQueryBuilder($join)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->from($this->getEntityName(), 'vls', $join === 'serializer' ? 'vls.code' : null);
        if ($join === 'serializer') {
            $qb->leftJoin('vls.children', 'vls_chd', 'WITH', 'vls.language = vls_chd.language');
            $qb->leftJoin('vls_chd.children', 'vls_chd_chd');
        }
        $qb->leftJoin('vls.parent', 'vls_par');
        $this->setSelect($qb, $join);

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param $join
     */
    protected function setSelect(QueryBuilder $qb, $join)
    {
        if ($join === 'stmt') {
            $qb->select('vls.code, vls.label, vls.value');
        } else {
            $qb->select('vls');
            if ($join == 'serializer') {
                $qb->addSelect('vls_chd', 'vls_chd_chd');
            }
        }
    }

    /**
     * @param  array        $criteria
     * @param $join
     * @param  array        $parameters
     * @return QueryBuilder
     */
    private function getQueryBuilderByCriteria(array $criteria, $join, array $parameters = array())
    {
        $parameters = new ParameterBag($parameters);
        $qb = $this->initQueryBuilder($join);
        if ($join === 'serializer' && isset($criteria['children'])) {
            $this->parseCriteria($qb, 'vls_chd', $criteria['children']);
            unset($criteria['children']);
        } elseif (isset($criteria['parent'])) {
            $this->parseCriteria($qb, 'vls_par', $criteria['parent']);
            unset($criteria['parent']);
        } else {
            //$qb->andWhere('vls.valuelist IS NULL');
        }
        $this->parseCriteria($qb, 'vls', $criteria);
        $qb->add('orderBy', $parameters->get('order_by', 'vls.label ASC'));
        if ($parameters->has('limit')) {
            $qb->setMaxResults($parameters->get('limit'));
        }

        return $qb;
    }

    /**
     * @param $criteria
     * @param $join
     * @param $parameters
     * @return \Doctrine\ORM\Query
     */
    public function getQueryByCriteria($criteria, $join, $parameters)
    {
        return $this->getQueryBuilderByCriteria($criteria, $join, $parameters)->getQuery();
    }

    /**
     * @param $criteria
     * @param $join
     * @param $parameters
     * @return mixed
     */
    public function findOneByCriteria($criteria, $join, $parameters)
    {
        return $this->getQueryByCriteria($criteria, $join, $parameters)->getOneOrNullResult();
    }

    /**
     * @param  array  $criteria
     * @param  string $join
     * @param  array  $parameters
     * @return array
     */
    public function findByCriteria(array $criteria, $join = 'default', array $parameters = array())
    {
        $qb = $this->getQueryBuilderByCriteria($criteria, $join, $parameters);
        $query = $qb->getQuery();
        if (isset($parameters['use_result_cache']) && $parameters['use_result_cache'] === true) {
            $query->useResultCache(true, null, 'valuelist');
        }

        return $query->getResult();
    }

    /**
     * @param  array  $criteria
     * @param  string $type
     * @param  array  $parameters
     * @return array
     */
    public function findStmtByCriteria(array $criteria, $type = 'default', array $parameters = array())
    {
        $qb = $this->getQueryBuilderByCriteria($criteria, 'stmt', $parameters);
        $stmt = $qb->getQuery()->iterate();
        $i = 0;
        $results = array();
        while ($row = $stmt->next()) {
            $this->buildStmtRow($results, $row[$i], $type);
            $i++;
        }

        return $results;
    }

    /**
     * @param array $results
     * @param array $row
     * @param $type
     */
    private function buildStmtRow(array &$results, array $row, $type)
    {
        if ($type === 'code' || $type === 'value') {
            $results[$row[$type]] = $row['label'];
        } else {
            $results[$row['code']] = array('label' => $row['label']);
            if ($row['value'] !== null) {
                $results[$row['code']]['value'] = $row['value'];
            }
        }
    }
}
