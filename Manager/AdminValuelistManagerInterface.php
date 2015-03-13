<?php

namespace Nuxia\ValuelistBundle\Manager;

use Nuxia\ValuelistBundle\Entity\Valuelist;

interface AdminValuelistManagerInterface extends ValuelistManagerInterface
{
    /**
     * @param  string $action
     * @param  string $category
     *
     * @return bool
     */
    public function isActionExistsOnCategory($action, $category);

    /**
     * @return array
     */
    public function getCategoryMap();

    /**
     * @param  Valuelist $valuelist
     *
     * @return bool
     */
    public function delete(Valuelist $valuelist);
}
