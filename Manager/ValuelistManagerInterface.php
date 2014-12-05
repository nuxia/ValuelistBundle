<?php

namespace Nuxia\ValuelistBundle\Manager;

interface ValuelistManagerInterface
{
    /**
     * @param string $locale
     * @param string $category
     * @param string $parent
     * @param string $type
     * @param bool   $useCache
     *
     * @return array
     */
    public function getValuelist($locale, $category, $parent = 'null', $type = 'default', $cache = true);

    /**
     * @param string $locale
     * @param string $category
     * @param string $code
     * @param string $parent
     *
     * @return null|array
     */
    public function getValue($locale, $category, $code, $parent = 'null');
}
