<?php

namespace Nuxia\ValuelistBundle\Pager;

use Nuxia\Component\Doctrine\Manager\ControllerManagerInterface;
use Nuxia\Component\Pager\BasePaginator;
use Nuxia\Component\Pager\PaginatorInterface;

class ValuelistPaginator extends BasePaginator implements PaginatorInterface
{
    /**
     * @var ControllerManagerInterface
     */
    protected $valuelistManager;

    /**
     * @var int
     */
    protected $limit;

    /**
     * @param ControllerManagerInterface $valuelistManager
     */
    public function setValuelistManager(ControllerManagerInterface $valuelistManager)
    {
        $this->valuelistManager = $valuelistManager;
    }

    /**
     * @param $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * {@inheritdoc}
     */
    public function createPaginator(array $criteria = array(), $type = 'list')
    {
        $target = $this->valuelistManager->getPaginatorTarget($criteria, $type);

        return $this->doPaginate($target, $this->limit);
    }
}
