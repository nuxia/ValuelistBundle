<?php

namespace Nuxia\ValuelistBundle\Manager;

interface AdminValuelistManagerInterface extends ValuelistManagerInterface
{
    /**
     * @param $action
     * @param $category
     * @return mixed
     */
    public function isActionExistsOnCategory($action, $category);
}
