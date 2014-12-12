<?php

namespace Nuxia\ValuelistBundle\Helper;

use Nuxia\ValuelistBundle\Entity\Valuelist;

class ValuelistParser
{
    private function __construct()
    {
        // Empêche l'instanciation de la classe
    }

    /**
     * @param Valuelist[] $valuelist
     *
     * @return array
     */
    public static function valuelistToTreeChoices(array $valuelist)
    {
        $choices = array();
        foreach ($valuelist as $value) {
            $parent = $value->getParent();
            if ($parent === null) {
                continue;
            }
            if (!isset($choices[$parent->getLabel()])) {
                $choices[$parent->getLabel()] = array();
            }
            $choices[$parent->getLabel()][$value->getCode()] = $value->getLabel();

        }
        return $choices;
    }
}
