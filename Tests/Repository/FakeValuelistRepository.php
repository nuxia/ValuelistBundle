<?php

namespace Nuxia\ValuelistBundle\Tests\Repository;

use Nuxia\Component\Parser;

class FakeValuelistRepository
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @var int
     */
    private $queriesCount = 0;

    public function __construct()
    {
        $this->data = Parser::parseYaml('Nuxia\ValuelistBundle', 'Tests/Repository/valuelist_data.yml');
    }

    public function getQueriesCount()
    {
        return $this->queriesCount;
    }

    public function getResults(array $criteria, $code = null)
    {
        $this->queriesCount++;

        $results = $code === null ? array() : null;
        foreach ($criteria['language'] as $language) {
            if (!isset($this->data[$language])) {
                continue;
            }
            if (!isset($this->data[$language][$criteria['category']])) {
                continue;
            }

            $parent = isset($criteria['parent']) ? $criteria['parent']['code'] : 'no-parent';
            $categoryData = $this->data[$language][$criteria['category']][$parent];

            if ($code !== null) {
                if (!isset($categoryData[$code])) {
                    continue;
                }

                return $categoryData[$code];
            } else {
                $results = array_merge($results, $categoryData);
            }
        }

        return $results;
    }

    public function findStmtByCriteria(array $criteria)
    {
        return $this->getResults($criteria);
    }
}
